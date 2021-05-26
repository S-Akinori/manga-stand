<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($story_id) {
        $comments = Comment::where('story_id', $story_id)->get();
        foreach($comments as $comment) {
            $comment['name'] = $comment->user->name;
        }
        return response()->json($comments);
    }

    public function store(Request $request) {
        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'story_id' => $request->story_id,
            'content' => $request->content,
        ]);

        return response()->json($comment);
    }
}