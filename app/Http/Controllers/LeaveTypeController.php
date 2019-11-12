<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveType;

class LeaveTypeController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(){
    return view('admin.leavetype',
      ['data' => LeaveType::all()]
    );
  }

  public function add(Request $req){

    // check for duplicate code
    $dup = LeaveType::where('code', $req->code)->first();

    if($dup){
      return redirect()->back()->withInput()->withErrors(['code' => 'Already exist']);
    }

    $nuleave = new LeaveType;
    $nuleave->code = $req->code;
    $nuleave->descr = $req->descr;
    $nuleave->hours_value = $req->hours_value;
    $nuleave->created_by = $req->user()->id;
    $nuleave->save();

    return redirect(route('leave.list', [], false))->with(['alert' => $req->code . ' added']);
  }

  public function edit(Request $req){

    $oleave = LeaveType::find($req->id);

    if($oleave){
      if($req->code != $oleave->code){
        // check if code duplicate with others
        $dup = LeaveType::where('code', $req->code)
          ->where('id', '!=', $req->id)->first();

        if($dup){
          return redirect(route('leave.list', [], false))->with(['alert' => 'Code duplicate with existing leave type']);
        }

        $oleave->code = $req->code;
      }

      $oleave->descr = $req->descr;
      $oleave->hours_value = $req->hours_value;
      $oleave->save();

      return redirect(route('leave.list', [], false))->with(['alert' => $req->code . ' updated']);

    } else {
      return redirect(route('leave.list', [], false))->with(['alert' => 'That leave type no longer exist']);
    }

  }

  public function del(Request $req){

    $oleave = LeaveType::find($req->id);
    if($oleave){
      $oname = $oleave->code;
      $oleave->deleted_by = $req->user()->id;
      $oleave->save();
      $oleave->delete();

      return redirect(route('leave.list', [], false))->with(['alert' => $oname . ' deleted']);

    } else {
      return redirect(route('leave.list', [], false))->with(['alert' => 'That leave type no longer exist']);
    }

  }
}
