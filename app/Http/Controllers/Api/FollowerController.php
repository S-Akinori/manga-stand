<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function hasFollowed($following_id, $followed_id) {
        $hasFollowed = Follower::where('following_id', $following_id)->where('followed_id', $followed_id)->exists();
        return response()->json($hasFollowed);
    }

    public function follow(Request $request) {
        return Follower::create([
            'following_id' => $request->following_id,
            'followed_id' => $request->followed_id,
        ]);
    }

    public function unfollow($following_id, $followed_id) {
        $follower = Follower::where('following_id', $following_id)->where('followed_id', $followed_id);
        $follower->delete();
        return response()->json($follower);
    }

    public function indexByFollowings($following_id) {
        $followings = Follower::where('following_id', $following_id)->get();
        $ids = [];
        foreach($followings as $item) {
            $ids[] = $item->followed_id;
        }
        $followings = User::whereIn('id', $ids)->get();
        return response()->json($followings);
    }

    public function indexByFollowers() {

    }
}
