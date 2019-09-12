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
use App\common\GDWActions;
use App\Api\V1\Controllers\BookingHelper;

class TStaffController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  // staff homepage
  public function index(Request $rq){
    $c_staff_id = Session::get('staffdata')['id'];
    if($rq->filled('staff_id')){
      $s_staff_id = $rq->staff_id;
    } else {
      $s_staff_id = Session::get('staffdata')['id'];
    }

    // get subordinates
    $sublist = Subordinate::where('superior_id', $s_staff_id)->get();

    $user = User::find($s_staff_id);
    if(isset($user->curr_checkin)){
      $bh = new BookingHelper;
      $lastloc = $bh->getCheckinMinimal($user->curr_checkin);
      // dd($lastloc);
    } else {
      $lastloc = 'N/A';
    }

    // build the graph lul
    $gdata = GDWActions::getActSummary($s_staff_id, date('Y-m-d'));
    // dd($gdata);
    $schart = app()->chartjs
         ->name('barChartTest')
         ->type('doughnut')
         ->size(['width' => 400, 'height' => 150])
         ->labels($gdata['label'])
         ->datasets([
             [
                 'label' => 'Hours Spent',
                 'backgroundColor' => $gdata['bg'],
                 'borderColor' => '#000',
                 'data' => $gdata['data']
             ]
         ])
         ->options([
           'responsive' => true,
           'title' => [
             'display' => true,
             'text' => 'Number of hours spent this month',
           ],
           'tooltips' => [
             'mode' => 'index',
             'intersect' => true,
           ],
           'hover' => [
             'mode' => 'nearest',
             'intersect' => true,
           ],
         ]);



    $final = [
      'staff_id' => $s_staff_id,
      'chart' => $schart,
      'subords' => $sublist,
      'user' => $user,
      'cuser' => $c_staff_id,
      'currcekin' => $lastloc
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

}
