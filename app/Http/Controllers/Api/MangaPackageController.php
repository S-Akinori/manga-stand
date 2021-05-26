<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Manga;
use App\Models\MangaPackage;
use App\Models\MangaVolume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MangaPackageController extends Controller
{
    const DATA_CHUNK = 10;

    public function index() {
        // latest
        $manga_package_latest = MangaPackage::orderBy('id', 'desc')->take(self::DATA_CHUNK)->get();
        $manga_package_favorite = MangaPackage::orderBy('favorites', 'desc')->take(self::DATA_CHUNK)->get();
        return response()->json(['latestPackage' => $manga_package_latest, 'favoritePackage' => $manga_package_favorite]);
    }

    public function show($id) {
        $manga_package = MangaPackage::find($id);
        if(!$manga_package->volumes) {
            return response()->json(['package' => $manga_package]);
        }
        $author = User::find($manga_package->user_id);
        $manga_volumes = MangaVolume::where('package_id', $id)->orderBy('id', 'asc')->get();
        $is_favorite = Favorite::where('user_id', Auth::user()->id)->where('package_id', $id)->exists();
        $manga = Manga::where('volume_id', $manga_volumes[0]->id)->orderBy('story', 'asc')->get();
        return response()->json(['package' => $manga_package, 'volumes' => $manga_volumes, 'manga' => $manga, 'isFavorite' => $is_favorite, 'author' => $author]);
    }

    public function edit($id) {
        $manga_package = MangaPackage::find($id);
        if(!$this->isAuth($manga_package->user_id)) {
            return response()->json([
                'name' => 'auth',
                'message' => 'Could not create a package',
                'redirect' => '/'
            ], 422);
        }
        return response()->json($manga_package);
    }

    public function store(Request $request) {
        $is_title = MangaPackage::where('title', $request->title)->exists();
        if($is_title) {
            return response()->json([
                'name' => 'title',
                'message' => 'This title exists'
            ], 422);
        }

        $target = [' ', '/', '.', '_', ','];
        $package_name = str_replace($target, '-', $request->title);
        $dir_path = 'storage/manga/' . $package_name;
        $dir = mkdir($dir_path);
        $dir_path = '/' . $dir_path;

        $package_img_path = $request->file('file')->store('public/manga/' . $package_name);
        $package_img_path = str_replace('public', '/storage', $package_img_path);

        if($dir) {
            $manga_package = MangaPackage::create([
                'user_id' => Auth::user()->id,
                'dir_path' => $dir_path,
                'title' => $request->title,
                'package_img_path' => $package_img_path,
                'description' => $request->description,
                'category' => $request->category
            ]);
        } else {
            return response()->json([
                'name' => 'whole',
                'message' => 'Could not create a package'
            ], 422);
        }

        return response()->json($manga_package);

    }

    public function update(Request $request, $id) {
        $manga_package = MangaPackage::findOrFail($id);
        $manga_package->title = $request->title;
        $manga_package->description = $request->description;
        $manga_package->category = $request->category;
        $file = $request->file('file');
        if($file) {
            $img_path = $manga_package->package_img_path;
            $img_path = str_replace('/storage', 'storage', $manga_package->package_img_path);
            unlink($img_path);
            $dir_path = str_replace('/storage', 'public', $manga_package->dir_path);
            $img_path = $file->store($dir_path);
            $img_path = str_replace('public', '/storage', $img_path);
            $manga_package->package_img_path = $img_path;
        }
        
        $manga_package->save();

        return response()->json($manga_package);
    }

    // public function categoryIndex($category) {
    //     if($category == 'all') {
    //         $manga_package = MangaPackage::orderBy('id', 'desc')->take(30)->get();
    //     } else {
    //         $manga_package = MangaPackage::where('category', 'like', '%'.$category.'%')->take(30)->get();
    //     }
    //     return response()->json($manga_package);
    // }

    // public function favoriteIndex() {
    //     $packages = Favorite::where('user_id', Auth::user()->id)->get();
    //     $packages_ids = [];
    //     foreach($packages as $item) {
    //         $package_ids[] = $item->package_id;
    //     }
    //     $manga_package = MangaPackage::whereIn('id', $package_ids)->get();

    //     return response()->json($manga_package);
    // }

    public function indexByLatest() {
        $manga_packages = MangaPackage::orderBy('id', 'desc')->take(30)->get();
        return response()->json($manga_packages);
    }

    public function indexByFavorite($id = null) {
        if($id == null) {
            $manga_packages = MangaPackage::orderBy('favorites', 'desc')->get();
        } else {
            $packages = Favorite::where('user_id', $id)->get();
            $packages_ids = [];
            foreach($packages as $item) {
                $package_ids[] = $item->package_id;
            }
            $manga_packages = MangaPackage::whereIn('id', $package_ids)->get();
        }
        return response()->json($manga_packages);
    }

    public function indexByTitle($keyword) {
        $manga_packages = MangaPackage::where('title', 'like', '%'.$keyword.'%')->get();
        return response()->json($manga_packages);
    }

    public function indexByAuthor($username) {
        $user = User::where('username', $username)->first();
        $manga_package = MangaPackage::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        return response()->json($manga_package);
    }

    public function indexByCategory($category) {
        if($category == 'all') {
            $manga_packages = MangaPackage::orderBy('id', 'desc')->get();
        } else {
            $manga_packages = MangaPackage::where('category', 'like', '%'.$category.'%')->get();
        }
        return response()->json($manga_packages);
    }

    public function allIndex() {
        $authors = User::pluck('name');
        $manga_titles = MangaPackage::pluck('title');
        return response()->json(['authors' => $authors, 'mangaTitles' => $manga_titles]);
    }

    private function isAuth($user_id) {
        return $user_id == Auth::user()->id;
    }
}
