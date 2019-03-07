<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TStaffController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  // staff homepage
  public function index(){
    return \Auth::user();
  }

  // ========= TASK management ==========

  // task summary
  public function taskIndex(Request $req){

  }

  public function addTask(Request $req){
    // return view
  }

  public function closeTask(Request $req){

  }

  // ========= Activity management ===========

  // daily activity management
  public function activitySummary(Request $req){

  }

  public function addActivity(Request $req){

  }

  public function deleteActivity(Request $req){

  }

}
