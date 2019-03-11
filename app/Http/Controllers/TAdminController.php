<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskCategory;
use App\building;
use App\User;
use Session;

class TAdminController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  // main admin page
  public function index(){
    return view('admin.index');
    // return Session::get('staffdata')['role'];
  }

  // -------- task type Management ---------
  // list all current task, and a form to add new
  public function showTaskManagement(){
    $tasklist = TaskCategory::where('status', 1)->get();
    return view('admin.tasktype', ['currtasklist' => $tasklist]);
  }

  // add the submitted task type, then redirect back to showTaskManagement
  public function doTaskMgmtAdd(Request $req){
    $tt = new TaskCategory;
    $tt->descr = $req->descr;
    $tt->remark = $req->remark;
    $tt->status = 1;

    $tt->save();

    $tasklist = TaskCategory::where('status', 1)->get();
    return view('admin.tasktype', ['currtasklist' => $tasklist]);

  }

  // delete task type. more like, change the status to inactive
  public function disableTaskType(Request $req){
    $task = TaskCategory::where('id', $req->taskid)->first();
    $task->status = 0;
    $task->save();

    return redirect(route('admin.tt', [], false));
  }


  // ------- ROLES --------
  public function showStaffRole(){
    // get the list of buildings
    $blist = building::all();
    $myrole = Session::get('staffdata')['role'];

    return view('admin.assignrole', ['blist' => $blist, 'role' => $myrole]);
  }

  public function findStaff(Request $req){

  }

  public function assignRole(Request $req){
    $text = str_replace("\r\n", "\n", trim($req->staffs));
    $stafflist = explode("\n", $text);

    foreach($stafflist as $astaff){
      $dump = $this->doUpdateUser($astaff, '', $req->cbfloor, $req->srole);
    }


    return $stafflist;
  }

  // create the user profile if they never logged in yet
  public function updateUser(Request $req){

  }

  private function doUpdateUser($staff_no, $lob, $floor_list, $srole){
    // first, check if the user exist
    $thestaff = User::where('staff_no', $staff_no)->first();

    if($thestaff){

    } else {
      // not exist. create new
      $thestaff = new User;
      $thestaff->staff_no = $staff_no;
      $thestaff->status = 1;
    }

    // then update the data
    $thestaff->role = $srole;
    // $thestaff->lob = $lob;
    $thestaff->allowed_building = $floor_list;
    $thestaff->save();

    return $thestaff;
  }

  // -------- building mgmt ----
  public function buildingIndex(){
    // fetch all current list of buildings
    $buildlist = building::all();

    // then call the view
    return view('admin.place', ['buildlist' => $buildlist]);
  }

  public function addBuilding(Request $req){

  }

  public function delBuilding(Request $req){

  }

}
