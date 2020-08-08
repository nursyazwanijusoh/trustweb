<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\StaffLeave;
use App\SapLeaveInfo;
use App\LeaveType;
use \Carbon\Carbon;
use App\common\UserRegisterHandler;
use App\common\GDWActions;

class StaffLeaveController extends Controller
{



  public function list(Request $req){
    $this->isAllowed($req, $req->user());
    $sid = $req->filled('staff_id') ? $req->staff_id : $req->user()->id;

    $user = User::find($sid);
    if($user){
      $jeniscuti = LeaveType::all();
      $manualcuti = StaffLeave::where('user_id', $user->id)->where('is_manual', true)->get();
      $loadedcuti = StaffLeave::where('user_id', $user->id)->where('is_manual', false)->get();
      $sapcuti = SapLeaveInfo::where('personel_no', $user->persno)->get();


      return view('gwd.leaves', [
        'staff' => $user,
        'mcuti' => $manualcuti,
        'lcuti' => $loadedcuti,
        'scuti' => $sapcuti,
        'ltype' => $jeniscuti
      ]);
    } else {
      abort(403);
    }


  }

  public function add(Request $req){
    $this->isAllowed($req, $req->user());

    // dd($req->all());

    $user = User::find($req->staff_id);
    if($user){

    } else {
      return redirect()->back()->withInput()->with([
        'a_type' => 'warning',
        'alert' => 'user 404'
      ]);
    }

    $cdate = new Carbon($req->tdate);
    $ldate = new Carbon($req->fdate);
    $cdate->addSecond();

    $daterange = new \DatePeriod(
      $ldate,
      \DateInterval::createFromDateString('1 day'),
      $cdate
    );

    $leavetype = LeaveType::find($req->livtaip);
    if($leavetype){

    } else {
      return redirect()->back()->withInput()->with([
        'a_type' => 'warning',
        'alert' => 'leave type 404'
      ]);
    }

    $days = [];
    // create the leave entry
    $mcuti = new StaffLeave;
    $mcuti->user_id = $user->id;
    $mcuti->start_date = $req->fdate;
    $mcuti->end_date = $req->tdate;
    $mcuti->leave_type_id = $leavetype->id;
    $mcuti->created_by = $req->user()->id;
    $mcuti->is_manual = true;
    $mcuti->remark = $req->remark;
    $mcuti->save();

    foreach($daterange as $aday){
      $dp = GDWActions::GetDailyPerfObj($user->id, $aday);
      // zerorize the expected hours
      $dp->expected_hours = 0;
      // mark this as manual zerorize
      $dp->zerorized = true;
      $dp->is_off_day = true;
      $dp->leave_type_id = $leavetype->id;
      $dp->save();
    }

    return redirect(route('mleave.list', ['staff_id' => $user->id], false))
      ->with([
        'a_type' => 'success',
        'alert' => 'expected hours zerorized'
      ]);

  }

  public function del(Request $req){

  }

  private function isAllowed($req, $staff){
    // allow admin
    if($req->user()->role < 2){
      return true;
    }

    if(UserRegisterHandler::IsCaretaker($req->user(), $staff)){
      return true;
    } else {
      abort(403);
    }



  }
}
