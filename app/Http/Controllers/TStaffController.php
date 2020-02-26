<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\Controllers\UserController;
use Session;
use App\Task;
use App\TaskCategory;
use App\ActivityType;
use App\User;
use App\Activity;
use App\Subordinate;
use App\DailyPerformance;
use App\StaffLeave;
use App\PublicHoliday;
use App\common\GDWActions;
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

    if($rq->filled('staff_id')){
      $s_staff_id = $rq->staff_id;
      if($s_staff_id != $c_staff_id){
        $isvisitor = true;
      }
    } else {
      $s_staff_id = $rq->user()->id;
    }

    $user = User::find($s_staff_id);

    // get subordinates
    $sublist = GDWActions::GetSubordsPerf($s_staff_id);

    // build the graph lul
    $cdate = new Carbon();
    $ldate = new Carbon();

    $daterange = new \DatePeriod(
      $cdate->subDays(7),
      \DateInterval::createFromDateString('1 day'),
      $ldate
    );

    $gdata = GDWActions::GetStaffRecentPerf($s_staff_id, $daterange);

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
                 'data' => $gdata
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
             'intersect' => true,
           ],
           'scales' => [
             'xAxes' => [[
               'display' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Time',
               ]
             ]],
             'yAxes' => [[
               'display' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Seat Count',
               ]
             ]]
           ]
         ]);


    // calendar
    $evlist = [];
    $counter = rand(0, 12);
    $last3month = new Carbon();
    $last3month->subMonths(3);

    // first load the public holiday
    $allph = PublicHoliday::all();
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
      ->get();

    foreach ($daysim as $key => $value) {
     $counter++;
     $bgcollll = 'rgba(0, 0, 255, 0.5)';

     if($value->expected_hours == 0 && $value->actual_hours > 0){
       $bgcollll = 'rgba(0, 255, 132, 0.5)';
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

    $superi = User::where('persno', $user->report_to)->first();

    $cds = \Calendar::addEvents($evlist);
    $todaydf = GDWActions::GetDailyPerfObj($s_staff_id, $ldate);
    $todayperc = 0;
    if($todaydf->expected_hours == 0){
      if($todaydf->actual_hours > 0){
        $todayperc = 120;
      } else {
        $todayperc = 120;
      }
    } else {
      $calcperf = $todaydf->actual_hours / $todaydf->expected_hours * 100;
      $todayperc = intval($calcperf);
    }

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
      'todayperc' => $todayperc
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
    if($req->filled('input')){
      $hdrh = new \App\common\HDReportHandler;
      $data = $hdrh->findStaff($req->input);

      // dd($data);

      if($data->count() == 1){
        $auser = $data->shift();

        return redirect(route('staff', ['staff_id' => $auser->id], false));
      } elseif($data->count() == 0){
        return view('staff.find', ['result' => '404']);
      } else {
        return view('staff.find', ['result' => $data]);
      }

      /*
      if($data['type'] == 'no'){
        return redirect(route('staff', ['staff_id' => $data['data']->id], false));
      } else {
        if($data['data']->count() == 0){
          return view('staff.find', ['result' => '404']);
        } elseif ($data['data']->count() == 1) {
          $astaff = $data['data']->shift();
          return redirect(route('staff', ['staff_id' => $astaff->id], false));
        } else {
          return view('staff.find', ['result' => $data['data']]);
        }

      }
      */

    } else {
      return view('staff.find', ['result' => 'empty']);
    }
  }

}
