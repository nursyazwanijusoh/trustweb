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
        return view('guide');
    }

    function playground(){
      // $eksel = new \App\common\ExcelHandler('test.xlsx');
      // $eksel->addSheet('kosong', [], []);
      //
      // $bjob = new \App\BatchJob;
      // $bjob->job_type = 'contoh';
      // $bjob->status = 'New';
      // $bjob->attachment = $eksel->getBinary();
      // $bjob->save();
      //
      // dd($bjob);

      $bjob = \App\BatchJob::find(11);


      return \App\common\ExcelHandler::DownloadFromBin($bjob->attachment, 'nom.xlsx');
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

}
