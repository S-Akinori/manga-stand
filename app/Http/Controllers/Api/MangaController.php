<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\MangaPackage;
use App\Models\MangaVolume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MangaController extends Controller
{
    public function index($volume_id) {
        $manga = Manga::where('volume_id', $volume_id)->get();
        return response()->json($manga);
    }

    public function show($id) {
        $manga = Manga::find($id);
        $path = $manga->dir_path . '/*';
        $path = str_replace('/storage', 'storage', $path);
        $files = glob($path);

        $manga_next = Manga::where('package_id', $manga->package_id)->where('story', $manga->story + 1)->first();

        $user = User::find(Auth::user()->id);
        $user->point -= 10;
        $user->save();
        return response()->json(['manga' => $manga, 'files' => $files, 'mangaNext' => $manga_next]);
    }

    public function create($id) {
        $manga_package = MangaPackage::find($id);
        $manga_volume = MangaVolume::where('package_id', $id)->orderBy('volume', 'desc')->get();
        return response()->json(['package' => $manga_package, 'volume' => $manga_volume]);
    }

    public function store(Request $request) {
        $zip = new ZipArchive;
        $file = $request->file('file');
        if($zip->open($file) === true) {
            $package_id = $request->package_id;
            // $manga_package = MangaPackage::find($package_id);
            $manga_volume = MangaVolume::where('package_id', $package_id)->where('volume', $request->volume)->first();

            $dir_path = $manga_volume->dir_path;
            $dir_path = str_replace('/storage', 'storage', $dir_path) ;
            $zip->extractTo($dir_path);
            $dir_path = '/' . $dir_path;
            $dir_name = $file->getClientOriginalName();
            $ext = '.zip';
            $dir_name = str_replace($ext, '', $dir_name);
            $dir_path = $dir_path . '/' . $dir_name;
            $pages = $zip->count();
            $zip->close();

            $user_id = Auth::user()->id;
            $story = 3; //this is an example, this id must be a variable

            Manga::create([
                'user_id' => $user_id,
                'package_id' => $package_id,
                'dir_path' => $dir_path,
                'title' => $request->title,
                'volume_id' => $manga_volume->id,
                'story' => $story,
                'pages' => $pages
            ]);

            // add 1 to num in manga package

            //

            return 'Success';
        } else {
            $zip->close();
            return response()->json([
                'name' => 'file',
                'message' => 'Failed to upload this file'
            ], 422);
        }
    }
}
