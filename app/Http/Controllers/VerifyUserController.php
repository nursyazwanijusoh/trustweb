<?php

namespace App\Http\Controllers;

use App\VerifyUser;
use Illuminate\Http\Request;

class VerifyUserController extends Controller
{
  public function verify($token){
    $verifyUser = VerifyUser::where('token', $token)->first();
    if($verifyUser ){
        $user = $verifyUser->user;
        if(!$user->verified) {
            $verifyUser->user->verified = true;
            $verifyUser->user->status = 2;
            $verifyUser->user->save();
            return view('auth.approve', ['token' => true]);
        } elseif ($user->status != 1) {
          return view('auth.approve');
        } else {
            return view('auth.login', ['loginerror' => 'Registration already approved', 'type' => 'success']);
        }
    }else{
        return view('auth.login', ['loginerror' => 'Invalid Token', 'type' => 'danger']);
    }
  }
}
