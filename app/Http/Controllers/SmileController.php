<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use App\McoTravelReq;
use App\SapLeaveInfo;
use \Carbon\Carbon;
use App\User;
use DB;
use App\HappyReason;
use App\HappyType;

use Laravel\Passport\ClientRepository;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

class SmileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }
    public function index(Request $req)
    {
        return view('smile.index', []);
    }
    public function form(Request $req)
    {
        $reasons = HappyReason::where('type_id', $req->type)->get();
        $type = HappyType::where('id', $req->type)->first();

        // dd($req->type,$reasons,$type);
        return view('smile.form', ['reasons' => $reasons,'ty'=>$type]);
    }
    public function submit(Request $req)
    {
      dd($req);
      return view('smile.index', []);
    }




}
