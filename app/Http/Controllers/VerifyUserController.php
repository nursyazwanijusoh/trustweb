<?php

namespace App\Http\Controllers;

use App\VerifyUser;
use Illuminate\Http\Request;

class VerifyUserController extends Controller
{
  public function verify($token){
    $verifyUser = VerifyUser::where('token', $token)->first();
    if(isset($verifyUser) ){
        $user = $verifyUser->user;
        if(!$user->verified) {
            $verifyUser->user->verified = true;
            $verifyUser->user->status = 1;
            $verifyUser->user->save();
            $status = "Your e-mail is verified. You can now login.";
        }else{
            $status = "Your e-mail is already verified. You can now login.";
        }
    }else{
        return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
    }

    return redirect('/login')->with('status', $status);

  }
}
