<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VerifyMail;
use App\User;
use App\Partner;
use App\DailyPerformance;
use App\common\IopHandler;

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
        return view('guide');
    }

    function playground(Request $req){

      $df = \App\common\GDWActions::GetDailyPerfObj($req->user()->id, '2020-04-20');

      dd(\App\common\GDWActions::canAcceptThisAct($df, 5.1));

    }

    function welcome(){
      return view('welcome');
    }

    function booking_faq(){
      return view('booking_faq');
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
      return redirect(route('app.list', [], false));

    }

    public function hallofshame(Request $req){
      $today = date('Y-m-d');
      $dtop10 = DailyPerformance::whereDate('record_date', $today)
        ->orderBy('actual_hours', 'DESC')
        ->limit(10)->get();


      return view('halloffame', [
        'diarytop10' => $dtop10
      ]);
    }

    public function poip(Request $req){
      return IopHandler::ReverseGeo(2.788489299, 101.7182277);
    }

    public function getImage(Request $req){
      if($req->filled('staff_no')){
        return IopHandler::GetStaffImage($req->staff_no);
      }
    }


}
