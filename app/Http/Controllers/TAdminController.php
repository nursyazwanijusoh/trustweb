<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskCategory;
use App\building;

class TAdminController extends Controller
{
  public function __construct()
  {
      // $this->middleware('auth');
  }

  // main admin page
  public function index(){
    return view('admin.index');
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

  }


  // ------- ROLES --------
  public function showStaffRole(){

  }

  public function findStaff(Request $req){

  }

  public function assignRole(Request $req){

  }

  // create the user profile if they never logged in yet
  public function createUser(Request $req){

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
