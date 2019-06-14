<?php

namespace App\common;

use App\User;
use App\VerifyUser;
use App\Mail\VerifyMail;
use Illuminate\Support\Facades\Hash;

class UserRegisterHandler
{
  public static function register(array $data){
    $user = new User;
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->staff_no = $data['staff_no'];
    $user->password = Hash::make($data['password']);
    $user->verified = false;
    $user->isvendor = 1;
    $user->role = 3;
    $user->status = 2;
    $user->partner_id = $data['partner_id'];
    $user->save();

    $verify = new VerifyUser;
    $verify->user_id = $user->id;
    $verify->token = str_random(35);
    $verify->save();

    \Mail::to($user->email)->send(new VerifyMail($user));

    return $user;
  }

  public static function userLogin($username, $password){

  }



}
