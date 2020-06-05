<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\Controllers\UserController;
use Session;
use App\Task;
use App\TaskCategory;
use App\ActivityType;
use App\User;
use App\Involvement;
use App\PersonalSkillset;
use App\CommonSkillset;
use App\BauExperience;
use App\Activity;
use App\Subordinate;
use App\DailyPerformance;
use App\StaffLeave;
use App\PublicHoliday;
use App\common\GDWActions;
use App\common\UserRegisterHandler;
use App\Api\V1\Controllers\BookingHelper;
use \Carbon\Carbon;

class TStaffController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  // staff homepage
  public function index(Request $rq){
    $c_staff_id = $rq->user()->id;
    $isvisitor = false;
    $canseepnc = true;

    if($rq->filled('staff_id')){
      $s_staff_id = $rq->staff_id;
      if($s_staff_id != $c_staff_id){
        $isvisitor = true;
        $canseepnc = UserRegisterHandler::isInReportingLine($s_staff_id, $c_staff_id);
      }
    } else {
      $s_staff_id = $rq->user()->id;
    }

    if($rq->user()->role <= 1){
      $canseepnc = true;
    }

    $user = User::find($s_staff_id);

    // get subordinates
    $sublist = GDWActions::GetSubordsPerf($s_staff_id);
    $superi = User::where('persno', $user->report_to)->first();

    if($canseepnc == false){
      // skip the rest of the data since cannot see it anyway

      return view('staff.index', [
        'staff_id' => $s_staff_id,
        'subords' => $sublist,
        'user' => $user,
        'cuser' => $c_staff_id,
        'superior' => $superi,
        'canseepnc' => false
      ]);
    }


    // build the graph lul
    $cdate = new Carbon();
    $ldate = new Carbon();

    $daterange = new \DatePeriod(
      $cdate->subDays(7),
      \DateInterval::createFromDateString('1 day'),
      $ldate->addDay()
    );

    $gdata = GDWActions::GetStaffRecentPerf($s_staff_id, $daterange);
    $pgdata = [];
    foreach ($gdata as $key => $value) {
      $pgdata[] = $value['perc'];
    }

    $graphlabel = [];
    foreach ($daterange as $satudate) {
      array_push($graphlabel, date_format($satudate, 'j M'));
    }

    // dd($gdata);
    $schart = app()->chartjs
         ->name('barChartTest')
         ->type('line')
         // ->size(['width' => 400, 'height' => 400])
         ->labels($graphlabel)
         ->datasets([
             [
                 'label' => 'Productivity %',
                 'backgroundColor' => 'rgba(0, 255, 132, 0.5)',
                 'borderColor' => '#000',
                 'data' => $pgdata,
                 'lineTension' => 0
             ]
         ])
         ->options([
           'responsive' => true,
           'title' => [
             'display' => true,
             'text' => 'Recent Performance',
           ],
           'tooltips' => [
             'mode' => 'index',
             'intersect' => false,
           ],
           'hover' => [
             'mode' => 'nearest',
             'intersect' => false,
           ],
           'scales' => [
             'xAxes' => [[
               'display' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Date',
               ]
             ]],
             'yAxes' => [[
               'display' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => '%',
               ]
             ]]
           ]
         ]);


    // calendar
    $evlist = [];
    $counter = rand(0, 12);
    $last3month = new Carbon();
    $tuday = new Carbon();
    $last3month->subMonths(3);

    // first load the public holiday
    $allph = PublicHoliday::whereDate('event_date', '>', $last3month)->get();
    foreach ($allph as $key => $value) {
     $evlist[] = \Calendar::event(
       $value->name,
       true,
       new \DateTime($value->event_date),
       new \DateTime($value->event_date),
       $value->id,[
         'color' => 'rgba(94, 38, 6, 0.2)'
       ]
     );
    }

    // then the daily summary
    $daysim = DailyPerformance::where('user_id', $s_staff_id)
      ->whereDate('record_date', '>', $last3month)
      ->whereDate('record_date', '<=', $tuday)
      ->get();

    foreach ($daysim as $key => $value) {
     $counter++;
     $bgcollll = 'rgba(0, 0, 255, 0.5)';

     if($value->expected_hours == 0 && $value->actual_hours > 0){
       $bgcollll = 'rgba(0, 155, 0, 1)';
     } elseif($value->expected_hours > 0 && $value->actual_hours == 0){
       $bgcollll = 'rgba(255, 0, 0, 0.5)';
     }

     $evlist[] = \Calendar::event(
       $value->actual_hours . ' / ' . $value->expected_hours . ' hours',
       true,
       new \DateTime($value->record_date),
       new \DateTime($value->record_date),
       $value->id,[
         'color' => $bgcollll,
         'url' => route('staff.addact', ['dfid' => $value->id], false)
       ]
     );
    }

    // then load the personal cuti info,
    $personalcuti = StaffLeave::where('user_id', $s_staff_id)
      ->whereDate('start_date', '>', $last3month)
      ->get();

    foreach ($personalcuti as $key => $value) {

      $eeeedate = new Carbon($value->end_date);
      $eeeedate->addDay();

     $evlist[] = \Calendar::event(
       $value->LeaveType->descr,
       true,
       new \DateTime($value->start_date),
       new \DateTime($eeeedate),
       $value->id,[
         'color' => 'rgba(215, 215, 44, 0.8)'
       ]
     );
    }


    $cds = \Calendar::addEvents($evlist);
    $todaydf = GDWActions::GetDailyPerfObj($s_staff_id, new Carbon());
    $todayperc = 0;
    if($todaydf->expected_hours == 0){
      if($todaydf->actual_hours > 0){
        $todayperc = 120;
      } else {
        $todayperc = 100;
      }
    } else {
      $calcperf = $todaydf->actual_hours / $todaydf->expected_hours * 100;
      $todayperc = intval($calcperf);
    }

    // $pscoiunt = 0;
    // if($isvisitor == false){
    //   // cek ada nak kena approve skillset tak
    //   $subsids = User::where('report_to', $rq->user()->persno)->where('status', 1)->pluck('id');
    //   $pscoiunt = PersonalSkillset::whereIn('staff_id', $subsids)
    //     ->whereIn('status', ['N', 'C'])->count();
    // }

    $final = [
      'staff_id' => $s_staff_id,
      'chart' => $schart,
      'subords' => $sublist,
      'user' => $user,
      'cuser' => $c_staff_id,
      'superior' => $superi,
      'cds' => $cds,
      'isvisitor' => $isvisitor,
      'todaydf' => $todaydf,
      'todayperc' => $todayperc,
      'canseepnc' => $canseepnc
    ];
    // dd($final);

    return view('staff.index', $final);
  }

  // ========= TASK management ==========

  // task summary
  public function taskIndex(Request $req){
    $tasktype = TaskCategory::where('status', 1)->get();
    $staff_name = 'Not Found!!';
    $currtasklist = [];
    $def_staff_id = Session::get('staffdata')['id'];
    if($req->filled('staff_id')){
      $def_staff_id = $req->staff_id;
    }

    $staff = User::where('id', $def_staff_id)->first();
    if($staff){
      $staff_name = $staff->name;
      $currtasklist = Task::where('user_id', $def_staff_id)
        ->where('status', 1)->get();
      $closedtasklist = Task::where('user_id', $def_staff_id)
        ->where('status', 0)->get();
    }


    return view('staff.taskindex', ['tasktype' => $tasktype,
      's_staff_id' => $def_staff_id,
      'staff_name' => $staff_name,
      'currtasklist' => $currtasklist,
      'completedtasklist' => $closedtasklist
    ]);

  }

  public function addTask(Request $req){
    // return view
    $task = new Task;
    $task->name = $req->name;
    $task->remark = $req->remark;
    $task->task_cat_id = $req->task;
    $task->user_id = $req->s_staff_id;
    $task->created_by = Session::get('staffdata')['name'];
    $task->status = 1;
    $task->total_hours = 0;
    $task->save();

    return redirect(route('staff.t', ['staff_id' => $req->s_staff_id], false));

  }

  public function taskDetail(Request $req){
    $mystaffid = Session::get('staffdata')['id'];

    $task = Task::where('id', $req->task_id)->first();
    if($task){
      // get the activities
      $usercontrol = new UserController;
      // $acts = Activity::where('task_id', $req->task_id)->get();
      $acts = $usercontrol->getActivityList($req->task_id);

      // get the type
      $ttype = TaskCategory::where('id', $task->task_cat_id)->first();
      $ttypename = $ttype->descr;

      // check if current user is the owner of this task
      $lock = '';
      if($task->user_id != $mystaffid){
        $lock = 'disabled';
      }

      return view('staff.taskdetail', [
        'taskinfo' => $task,
        'activities' => $acts,
        'tasktype' => $ttypename,
        'lock' => $lock
      ]);

    } else {
      return response('Not found', 404);
    }
  }

  public function closeTask(Request $req){
    $task = Task::where('id', $req->task_id)->first();
    $task->status = 0;
    $task->save();

    return redirect(route('staff.tdetail', ['task_id' => $req->task_id], false));
  }

  // ========= Activity management ===========

  // daily activity management
  public function activitySummary(Request $req){

  }

  public function addActivity(Request $req){
    $mystaffid = Session::get('staffdata')['id'];


    // get the list of task
    $tasklist = Task::where('user_id', $mystaffid)->where('status', 1)->get();
    // list of activity type
    $acttypelist = ActivityType::where('status', 1)->get();
    $gottask = false;

    foreach($tasklist as $atask){
      $gottask = true;
      $atask['sel'] = '';
      if($req->filled('task_id')){
        if($atask->id == $req->task_id){
          $atask['sel'] = 'selected';
        }
      }
    }
    // return $tasklist;
    $curdate = date('Y-m-d');

    if($req->filled('alert')){
      return view('staff.addactivity', [
        'tasklist' => $tasklist,
        'curdate' => $curdate, 'alert' => 'y',
        'actlist' => $acttypelist,
        'gottask' => $gottask
      ]);
    }

    return view('staff.addactivity', [
      'tasklist' => $tasklist, 'curdate' => $curdate,
      'actlist' => $acttypelist, 'gottask' => $gottask
    ]);
  }

  public function doAddACtivity(Request $req){
    $act = new Activity;
    $act->task_id = $req->acttask;
    $act->date = $req->actdate;
    $act->act_type = $req->acttype;
    $act->hours_spent = $req->hours;
    $act->remark = $req->remark;
    $act->save();

    // update the task with total sum of hours of activities
    $sum = \DB::table('activities')->where('task_id', $req->acttask)->sum('hours_spent');
    $task = Task::where('id', $req->acttask)->first();
    $task->total_hours = $sum;
    $task->save();

    return redirect(route('staff.addact', ['alert' => 'y'], false));
  }

  public function deleteActivity(Request $req){

  }

  public function locHistory(Request $req){
    $staffid = $req->session()->get('staffdata')['id'];
    if($req->filled('staff_id')){
      $staffid = $req->staff_id;
    }
    $staffdata = User::findOrFail($staffid);

    $bh = new BookingHelper;
    $ch = $bh->getCheckinHistory($staffid);

    return view('staff.lochist', [
      'username' => $staffdata->name,
      'activities' => $ch
    ]);

  }

  public function rptFindStaff(Request $req){

    $skilist = CommonSkillset::all();
    $bauexp = BauExperience::all();
    $data = 'empty';

    if($req->filled('input')){
      $hdrh = new \App\common\HDReportHandler;
      $data = $hdrh->findStaff($req->input);

      if($data->count() == 1){
        $auser = $data->shift();

        return redirect(route('staff', ['staff_id' => $auser->id], false));
      } elseif($data->count() == 0){
        $data = '404';
      }

    }

    return view('staff.find', [
      'result' => $data,
      'skills' => $skilist,
      'exps' => $bauexp
    ]);
  }

  public function rptFindStaffWSkill(Request $req){

    $result = [];
    $heads = ['Division', 'Name', 'Position'];
    $params = [];
    $parame = [];
    $skillids = [];
    $nops = false;
    $noexp = false;


    $cariuser = User::query();

    if($req->filled('skid')){
      foreach ($req->skid as $key => $value) {
        $idss = CommonSkillset::find($value);
        array_push($heads, $idss->name);
        array_push($params, $idss->name);
        array_push($skillids, $idss->id);
        $cariuser->whereIn('id', PersonalSkillset::where('common_skill_id', $value)->where('level', '!=', 0)->pluck('staff_id'));
      }

    } else {
      $nops = true;
    }

    if($req->filled('expid')){
      $parame = BauExperience::find($req->expid)->pluck('name');

      foreach ($req->expid as $key => $value) {
        $cariuser->whereIn('id', \DB::table('bau_experience_user')->where('bau_experience_id', $value)->pluck('user_id'));
      }

    } else {
      $noexp = true;
    }

    if($nops == true && $noexp == true){
      return view('staff.skillfindresult', [
        'paramskill' => ['no', 'search'],
        'paramexp' => ['parameter', 'specified'],
        'result' => $result,
        'header' => ['press', 'back', 'and', 'search', 'again']
      ]);
    }

    // get the result
    $result = $cariuser->get();

    return view('staff.skillfindresult', [
      'paramskill' => $params,
      'paramexp' => $parame,
      'result' => $result,
      'header' => $heads,
      'skillids' => $skillids
    ]);

  }

  public function rptFindStaffWSkill2(Request $req){

    $psl = [];
    $exp = [];
    $params = [];
    $parame = [];
    $skillids = [];
    $nops = false;
    $noexp = false;

    if($req->filled('skid')){
      foreach ($req->skid as $key => $value) {
        $idss = CommonSkillset::find($value);
        array_push($params, $idss->name);
        array_push($skillids, $idss->id);

        $psldd = PersonalSkillset::where('common_skill_id', $value)->get();

        foreach($psldd as $atask){
          array_push($psl, [
            'ps_id' => $atask->id,
            'name' => $atask->User->name,
            'staff_id' => $atask->User->id,
            'division' => $atask->User->unit,
            'report_to_name' => $atask->User->Boss->name,
            'report_to_id' => $atask->User->Boss->id,
            'ps_name' => $atask->CommonSkill->name,
            'ps_status' => $atask->sStatus(),
            'ps_level' => $atask->slevel(),
            'ps_plevel' => $atask->prev_approved()
          ]);
        }


      }

    } else {
      $nops = true;
    }

    if($req->filled('expid')){
      $parame = BauExperience::find($req->expid)->pluck('name');
      $exp = Involvement::whereIn('bau_experience_id', $req->expid)->get();

    } else {
      $noexp = true;
    }

    if($nops == true && $noexp == true){
      return view('staff.skillfindresult2', [
        'paramskill' => ['no', 'search'],
        'paramexp' => ['parameter', 'specified'],
        'psres' => [],
        'expres' => []
      ]);
    }

    return view('staff.skillfindresult2', [
      'paramskill' => $params,
      'paramexp' => $parame,
      'psres' => $psl,
      'expres' => $exp
    ]);

  }

}
