<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function register(Request $request) {

        $is_user = User::where('email', $request->email)->exists();
        if($is_user) {
            return response()->json([
                'name' => 'email',
                'message' => 'This email is already used'
            ], 422);
        } else {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verify_token' => sha1(uniqid()),
                'img_path' => '/storage/img/no-profile.png'
            ]);
            Mail::to($user->email)->send(new EmailVerification($user));
        }
        return $user;
    }

    public function resendEmail(Request $request) {

        $user = User::find($request->id);
        if($user) {
            $user->email_verify_token = sha1(uniqid());
            $user->save();
    
            Mail::to($user->email)->send(new EmailVerification($user));
            return ;
        } else {
            return response()->json([
                'name' => 'top',
                'message' => 'メールを送れませんでした。'
            ], 422);
        }
    }

    public function verifyEmail($token) {
        if(!$token) {
            $is_verified = false;
        } else {
            $user = User::where('email_verify_token', $token)->first();
            if(isset($user)) {
                $user->email_verified_at = Carbon::now();
                $user->email_verify_token = null;
                $user->save();
                $is_verified = true;
            } else {
                $is_verified = false;
            }
        }
        return response()->json(['user'=>$user, 'isVerified'=>$is_verified]);
    }
}
