<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VerifyMail;
use App\User;
use App\Partner;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    function playground(){
      return version('v1')->route('api.home', [], false);
    }

    function welcome(){
      return view('home');
    }

    public function listAdmins(){
      $adminlist = User::where('role', '<=', 2)->get();
      return view('adminlist', ['admins' => $adminlist]);
    }

    public function postreg(Request $req){
      return view('auth.verify', ['staff' => $req->staff]);
    }

    public function resend(Request $req){
      $user = User::findOrFail($req->staff);
      \Mail::to($user->email)->send(new VerifyMail($user));
      return view('auth.verify', ['staff' => $req->staff, 'resend' => true]);
    }

    public function mobregform(){
      $partn=Partner::all();
      return view('auth.mobreg', compact('partn'));
    }

    public function troll(){
      return view('troll');
    }

    public function info(){
      return redirect()->away('https://tmsoagit.tm.com.my/trUSt');
      
    }

}
