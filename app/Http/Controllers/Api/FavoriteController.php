<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Manga;
use App\Models\MangaPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request) {
        Favorite::create([
            'user_id' => Auth::user()->id,
            'package_id' => $request->package_id,
        ]);

        $manga_package = MangaPackage::find($request->package_id);

        $manga_package->favorites++;
        $manga_package->save();

        return 'Add Favorite';
    }

    public function destory($id) {
        Favorite::where('user_id', Auth::user()->id)->where('package_id', $id)->delete();

        $manga_package = MangaPackage::find($id);
        $manga_package->favorites--;
        $manga_package->save();

        return 'Delete Favorite';
    }
}
