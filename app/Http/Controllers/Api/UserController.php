<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($username) {
        if($username == null) {
            $user = User::find(Auth::user()->id);
        } else {
            $user = User::where('username', $username)->first();
        }
        return response()->json($user);
    }

    public function indexByName($name) {
        $user = User::where('name', 'like', '%'.$name.'%')->get();
        return response()->json($user);
    }

    public function updateEmail(Request $request) {
        $user = User::find($request->id);
        $user->email = $request->new_email;
        $user->save();

        return response()->json(['message' => 'メールアドレスを変更しました。']);
    }

    public function updatePassword(Request $request) {
        $user = User::find($request->id);
        if(!password_verify($request->current_password, $user->password)){
            return response()->json([
                'message' => 'Current Password dose not match'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'パスワードを設定しました']);
    }
}
