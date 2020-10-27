<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VerifyMail;
use App\User;
use App\Partner;
use App\News;
use App\Guide;
use App\DailyPerformance;
use App\GwdActivity;
use App\TaskCategory;
use App\ActivityType;
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

    public function policy(){
      return view('policy');
    }

    public function guides(){
      $gs = Guide::all()->sortBy('title');
      return view('guides', [
        'guide' => $gs
      ]);
    }

    function playground(Request $req){
      // $user = User::find($req->id);

      // $repot = \App\common\McoActions::FindAtLeastGm($user, $user);
      // dd($repot->name);
      //
      $ddas = new \App\Api\V1\Controllers\LdapHelper;
      // // // dd($user->persno);
      return $ddas->fetchUser($req->staff_no);
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
      $lastmon->subWeek();

      $mtop10 = \DB::table('daily_performances')
        ->select('user_id', \DB::raw('sum(actual_hours) as tot_hrs'), \DB::raw('sum(expected_hours) as exp_hrs'))
        ->whereDate('record_date', '>', $lastmon)
        ->whereDate('record_date', '<=', $today)
        // ->where('actual_hours', '>', 0)
        ->groupBy('user_id')
        ->orderBy('tot_hrs', 'DESC')
        ->limit(10)->get();


      $m10data = [];
      foreach($mtop10 as $au){
        $user = User::find($au->user_id);

        array_push($m10data, [
          'id' => $user->id,
          'name' => $user->name,
          'staff_no' => $user->staff_no,
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

    public function staffFancyReport(Request $req){
      if($req->filled('staff_no')){
        $user = User::where('staff_no', $req->staff_no)->first();
        if($user){
        } else {
          abort(404);
        }
      } else {
        $user = $req->user();
      }

      $end_date = new Carbon;
      if($req->filled('date')){
        $end_date = new Carbon($req->date);
      }

      $start_date = new Carbon($end_date);
      $start_date->subWeek()->addDay();


      $daterange = new \DatePeriod(
        $start_date,
        \DateInterval::createFromDateString('1 day'),
        (new Carbon($end_date))->addDay()
      );

      $exph = [];
      $acth = [];
      $daylabel = [];
      $dflist = [];
      // get the list of DFs
      foreach($daterange as $aday){
        $ad = GDWActions::GetDailyPerfObj($user->id, $aday);
        $dflist[] = $ad;
        $exph[] = $ad->expected_hours;
        $acth[] = $ad->actual_hours;
        $daylabel[] = $aday->format('d (D)');
      }

      // summary
      $gdata = GDWActions::GetStaffRecentPerf($user->id, $daterange);
      $pgdata = [];
      $weekact = 0;
      $weekexp = 0;
      foreach ($gdata as $key => $value) {
        $pgdata[] = $value['perc'];
        $weekact += $value['actual'];
        $weekexp += $value['expected'];
      }
      $weekperc = intval($weekexp == 0 ? 100 + ($weekact / (8 * 7) * 100) : $weekact / $weekexp * 100);


      // expected vs actual grap
      $datasets = array([
            'label' => 'Expected Hours',
            'data' => $exph,
            'backgroundColor' => "rgba(88, 88, 88, 0.5)",
            'borderColor' => "rgba(100, 100, 100, 0.7)",
          ],[
            'label' => 'Actual Hours',
            'data' => $acth,
            'backgroundColor' => "rgba(255, 255, 0, 0.5)",
            'borderColor' => "rgba(51, 204, 51, 0.7)",
          ]
        );

      $evsa_graph = app()->chartjs
           ->name('evsa_graph')
           ->type('bar')
           ->size(['width' => 400, 'height' => 200])
           ->labels($daylabel)
           ->datasets($datasets)
           ->options([
             'responsive' => true,
             'tooltips' => [
               'mode' => 'index',
               'intersect' => false,
             ],
             'hover' => [
               'mode' => 'nearest',
               'intersect' => true,
             ],
             'scales' => [
               'xAxes' => [[
                 'scaleLabel' => [
                   'display' => true,
                   'LabelString' => 'Date',
                 ]
               ]],
               'yAxes' => [[
                 'scaleLabel' => [
                   'display' => true,
                   'LabelString' => 'Sum Hours',
                 ]
               ]]
             ]
           ]);

      $tagtypedataset = [];
      // break by tag
      $tagsum = GwdActivity::groupBy('task_category_id')
        ->where('user_id', $user->id)
        ->whereBetween('activity_date', [$start_date->toDateString(), $end_date->toDateString()])
        ->select('task_category_id', \DB::raw('sum(hours_spent) as total'))->get();

      $taglbl = [];
      $tagdata = [];
      $tagid = [];
      $tagbg = [];
      $counter = rand(0, 12);
      foreach ($tagsum as $key => $value) {
        $counter++;
        $tagid[] = $value->task_category_id;
        $tc = TaskCategory::find($value->task_category_id);
        if($tc){
          $taglbl[] = $tc->descr;
        } else {
          $taglbl[] = $value->task_category_id;
        }
        $tagbg[] = GDWActions::getBgColor($counter);
        $tagdata[] = $value->total;
      }

      $bbt_pie = app()->chartjs
           ->name('bbytag')
           ->type('pie')
           ->size(['width' => 400, 'height' => 200])
           ->labels($taglbl)
           ->datasets([
               [
                   'label' => 'Hours Spent',
                   'backgroundColor' => $tagbg,
                   // 'borderColor' => '#000',
                   'data' => $tagdata
               ]
           ])
           ->options([
             'responsive' => true,
             'tooltips' => [
               'mode' => 'index',
               'intersect' => true,
             ],
             'hover' => [
               'mode' => 'nearest',
               'intersect' => true,
             ],
           ]);

       // break by act type
       $typesum = GwdActivity::groupBy('activity_type_id')
         ->where('user_id', $user->id)
         ->whereBetween('activity_date', [$start_date->toDateString(), $end_date->toDateString()])
         ->select('activity_type_id', \DB::raw('sum(hours_spent) as total'))->get();

       $typelbl = [];
       $typedata = [];
       $typebg = [];
       foreach ($typesum as $key => $value) {
         $counter++;
         $tc = ActivityType::find($value->activity_type_id);

         if($tc){
           $llabel = $tc->descr;
         } else {
           $llabel = $value->activity_type_id;
         }

         $typelbl[] = $llabel;

         // also find the tag_type sum for this
         $tagtypesums = [];
         foreach ($tagid as $key2 => $tag) {
           $tagtypesums[] = GwdActivity::where('user_id', $user->id)
             ->whereBetween('activity_date', [$start_date->toDateString(), $end_date->toDateString()])
             ->where('activity_type_id', $value->activity_type_id)
             ->where('task_category_id', $tag)->sum('hours_spent');
         }
         $lbgcolor = GDWActions::getBgColor($counter);
         $tagtypedataset[] = [
           'label' => $llabel,
           'data' => $tagtypesums,
           'backgroundColor' => $lbgcolor,
         ];
         $typebg[] = $lbgcolor;
         $typedata[] = $value->total;
       }

       $bbtype_pie = app()->chartjs
            ->name('bbytype')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels($typelbl)
            ->datasets([
                [
                    'label' => 'Hours Spent',
                    'backgroundColor' => $typebg,
                    // 'borderColor' => '#000',
                    'data' => $typedata
                ]
            ])
            ->options([
              'responsive' => true,
              'tooltips' => [
                'mode' => 'index',
                'intersect' => true,
              ],
              'hover' => [
                'mode' => 'nearest',
                'intersect' => true,
              ],
            ]);

      // type vs tag
      $tvt_height = 250;
      $tvt_type = 'bar';
      if(count($tagtypedataset) > 3){
        $tvt_height = 100 + count($tagtypedataset) * count($typelbl) * 10;
        $tvt_type = 'horizontalBar';
      }

      $tvt_graph = app()->chartjs
           ->name('tvt_graph')
           ->type($tvt_type)
           ->size(['width' => 700, 'height' => $tvt_height])
           ->labels($taglbl)
           ->datasets($tagtypedataset)
           ->options([
             'responsive' => true,
             'maintainAspectRatio' => false,
             'tooltips' => [
               'mode' => 'index',
               'intersect' => false,
             ],
             'hover' => [
               'mode' => 'nearest',
               'intersect' => true,
             ],
             'scales' => [
               'xAxes' => [[
                 'scaleLabel' => [
                   'display' => true,
                   'LabelString' => 'Activity Tag',
                 ]
               ]],
               'yAxes' => [[
                 'scaleLabel' => [
                   'display' => true,
                   'LabelString' => 'Sum Hours',
                 ]
               ]]
             ]
           ]);

      return view('hofstaff', [
        'user' => $user,
        'sdate' => $start_date->toDateString(),
        'edate' => $end_date->toDateString(),
        'weekexp' => $weekexp,
        'weekact' => $weekact,
        'weekperc' => $weekperc,
        'evsag' => $evsa_graph,
        'bbtpie' => $bbt_pie,
        'pptypepie' => $bbtype_pie,
        'tvtgraph' => $tvt_graph
      ]);

    }

    public function news(){

      $newlist = News::orderBy('created_at', 'DESC')->limit(10)->get();

      return view('news', ['news' => $newlist]);
    }




}
