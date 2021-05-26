<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\MangaController;
use App\Http\Controllers\Api\MangaPackageController;
use App\Http\Controllers\Api\MangaVolumeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/user/verify/{token}', [RegisterController::class, 'verifyEmail']);
Route::post('/user/verify/resend', [RegisterController::class, 'resendEmail']);
Route::post('/user/update/email', [UserController::class, 'updateEmail']);

Route::get('/user/{username}', [UserController::class, 'show']);
Route::get('/user/name/{name}', [UserController::class, 'indexByName']);

Route::get('/', [MangaPackageController::class, 'allIndex']);

// Route::get('/category/{category}', [MangaPackageController::class, 'categoryIndex']);
// Route::get('/favorite', [MangaPackageController::class, 'favoriteIndex']);

Route::post('/manga/store', [MangaController::class, 'store']);
Route::get('/manga/{volume_id}/index', [MangaController::class, 'index']);
Route::get('/manga/{id}/create', [MangaController::class, 'create']);
Route::get('/manga/{id}', [MangaController::class, 'show']);

Route::get('/manga-package/index', [MangaPackageController::class, 'index']);
Route::post('/manga-package/store', [MangaPackageController::class, 'store']);
Route::get('/manga-package/author/{author}', [MangaPackageController::class, 'indexByAuthor']);
Route::get('/manga-package/title/{keyword}', [MangaPackageController::class, 'indexByTitle']);
Route::get('/manga-package/latest/', [MangaPackageController::class, 'indexByLatest']);
Route::get('/manga-package/favorite/{id?}', [MangaPackageController::class, 'indexByFavorite']);
Route::get('/manga-package/category/{category}', [MangaPackageController::class, 'indexByCategory']);
Route::get('/manga-package/{id}/edit', [MangaPackageController::class, 'edit']);
Route::get('/manga-package/{id}', [MangaPackageController::class, 'show']);
Route::patch('/manga-package/{id}', [MangaPackageController::class, 'update']);


Route::get('/manga-volume/{package_id}/create', [MangaVolumeController::class, 'create']);
Route::post('/manga-volume/store', [MangaVolumeController::class, 'store']);
Route::get('/manga-volume/{id}', [MangaVolumeController::class, 'show']);

Route::post('/manga-package/{id}/favorite', [FavoriteController::class, 'store']);
Route::delete('/manga-package/{id}/favorite', [FavoriteController::class, 'destory']);

Route::get('/following/{following_id}/followed/{followed_id}', [FollowerController::class, 'hasFollowed']);
Route::get('/followings/{following_id}', [FollowerController::class, 'indexByFollowings']);
Route::post('/following', [FollowerController::class, 'follow']);
Route::delete('/following/{following_id}/followed/{followed_id}', [FollowerController::class, 'unfollow']);

Route::get('/comment/{story_id}', [CommentController::class, 'index']);
Route::post('/comment', [CommentController::class, 'store']);