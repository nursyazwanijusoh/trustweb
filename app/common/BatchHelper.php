<?php

namespace App\common;

use App\User;
use App\StaffPersMap;
use App\SapEmpProfile;
use App\SapLeaveInfo;
use App\StaffLeave;
use App\LeaveType;

class BatchHelper
{

  public static loadCutiData($staff_id = 1){
    $cutis = SapLeaveInfo::where('load_status', 'N')->get();

    foreach($cutis as $c){
      $user = User::where('persno', $c->personel_no)->first();
      if($user){
        $leavetype = LeaveType::where('code', $c->leave_code)->first();
        if($leavetype){

        } else {
          $leavetype = new LeaveType;
          $leavetype->code = $c->leave_code;
          $leavetype->descr = $c->leave_describtion;
          $leavetype->hours_value = 8;
          $leavetype->created_by = 1;
          $leavetype->save();
        }


        // REJECTED WITHDRAWN
        // check for existing cuti with same date

        $curcuti = StaffLeave::where('leave_type_id', $leavetype->id)
          ->whereDate('start_date', $c->date_start)
          ->whereDate('end_date', $c->date_end)
          ->where('user_id', $user->id)
          ->first();

        if($curcuti){
          // entry already exist. check if it's for reject or withdrawn
          if($c->status == 'REJECTED' || $c->status == 'WITHDRAWN'){

          }
        }
      }
    }
  }

}
