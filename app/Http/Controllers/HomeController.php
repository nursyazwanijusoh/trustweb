<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VerifyMail;
use App\User;
use App\Partner;
use App\DailyPerformance;
use \Carbon\Carbon;
use App\common\GDWActions;

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

      $user = User::find($req->id);

      // $repot = \App\common\McoActions::FindAtLeastGm($user, $user);
      // dd($repot->name);
      //
      $ddas = new \App\Api\V1\Controllers\LdapHelper;
      // // // dd($user->persno);
      return $ddas->fetchUser($user->staff_no);
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
        ->where('actual_hours', '>', 0)
        ->orderBy('actual_hours', 'DESC')
        ->limit(10)->get();

      foreach ($dtop10 as $key => $value) {
        $value->start_working = GDWActions::GetStartWorkTime($value->user_id, $today);
      }

      $lastmon = new Carbon;
      $lastmon->subMonth();

      $mtop10 = \DB::table('daily_performances')
        ->select('user_id', \DB::raw('sum(actual_hours) as tot_hrs'), \DB::raw('sum(expected_hours) as exp_hrs'))
        ->whereDate('record_date', '>', $lastmon)
        ->groupBy('user_id')
        ->orderBy('tot_hrs', 'DESC')
        ->limit(10)->get();


      $m10data = [];
      foreach($mtop10 as $au){
        $user = User::find($au->user_id);

        array_push($m10data, [
          'id' => $user->id,
          'name' => $user->name,
          'div' => $user->unit,
          'exp' => $au->exp_hrs,
          'act' => $au->tot_hrs
        ]);
      }


      return view('halloffame', [
        'diarytop10' => $dtop10,
        'montop'  => $m10data
      ]);
    }




}
