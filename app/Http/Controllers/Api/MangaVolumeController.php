<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MangaPackage;
use App\Models\MangaVolume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MangaVolumeController extends Controller
{

    public function create($package_id) {
        $latest_vol = MangaVolume::where('package_id', $package_id)->orderBy('volume', 'desc')->first();
        if(!$this->isAuth($latest_vol->user_id)) {
            return response('/');
        }
        return response()->json($latest_vol);
    }

    public function store(Request $request) {
        $package = MangaPackage::find($request->package_id);
        $dir_path = $package->dir_path . '/' . $request->volume;
        $dir_path = str_replace('/storage', 'public', $dir_path);
        $image_path = $request->file('file')->store($dir_path);
        $image_path = str_replace('public', '/storage', $image_path);
        $dir_path = str_replace('public', '/storage', $dir_path);

        MangaVolume::create([
            'user_id' => Auth::user()->id,
            'package_id' => $request->package_id,
            'volume' => $request->volume,
            'dir_path' => $dir_path,
            'image_path' => $image_path,
            'description' => $request->description
        ]);

        $package->volumes++;
        $package->save();

        return 'Success';
    }

    public function show($id) {
        $manga_volume = MangaVolume::find($id);
        return response()->json($manga_volume);
    }

    private function isAuth($user_id) {
        return $user_id == Auth::user()->id;
    }
}
