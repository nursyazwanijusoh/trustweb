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

class TStaffController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  // staff homepage
  public function index(Request $rq){
    if($rq->filled('staff_id')){
      $s_staff_id = $rq->staff_id;
    } else {
      $s_staff_id = Session::get('staffdata')['id'];
    }

    // get some summaries
    $opentaskcount = Task::where('user_id', $s_staff_id)
      ->where('status', 1)->count();
    $donetaskcount = Task::where('user_id', $s_staff_id)
      ->where('status', 0)->count();

    // get subordinates
    $sublist = Subordinate::where('superior_id', $s_staff_id)->get();

    $user = User::find($s_staff_id);

    return view('staff.index', [
      'staff_id' => $s_staff_id,
      'opentask' => $opentaskcount,
      'donetask' => $donetaskcount,
      'subords' => $sublist,
      'user' => $user
    ]);
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
    $task = Task::where('id', $req->task_id)->first();
    if($task){
      // get the activities
      $usercontrol = new UserController;
      // $acts = Activity::where('task_id', $req->task_id)->get();
      $acts = $usercontrol->getActivityList($req->task_id);

      // get the type
      $ttype = TaskCategory::where('id', $task->task_cat_id)->first();
      $ttypename = $ttype->descr;

      return view('staff.taskdetail', [
        'taskinfo' => $task,
        'activities' => $acts,
        'tasktype' => $ttypename
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

}
