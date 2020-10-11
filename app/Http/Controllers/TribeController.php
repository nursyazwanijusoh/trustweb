<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use App\McoTravelReq;
use App\SapLeaveInfo;
use \Carbon\Carbon;
use App\User;


use Laravel\Passport\ClientRepository;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

class TribeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }
    public function Home(Request $req){    
    
        return view('tribe.home', ['token' => 'here']);
      }


      public function validateToken(Request $req){    
        $user = $req->user();
        $token = $user->createToken('tribe')->accessToken;
        // $token = $user->createToken('trUSt')->accessToken;
        // $token = $user->tokens();
        //$token = Auth::user()->token();
       
         return view('tribe.home', ['token' => $token, ]);
      }

      public function vt(Request $req){    
        $user = $req->user();
        $token = $user->createToken('tribe')->accessToken;
    
         return $token;
      }
  

}