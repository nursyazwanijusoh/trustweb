<?php

namespace App\common;

use App\User;
use App\StaffPersMap;
use App\SapEmpProfile;
use App\SapLeaveInfo;
use App\StaffLeave;
use App\LeaveType;
use App\Unit;
use App\SubUnit;

class BatchHelper
{

  public static function loadCutiData($staff_id = 1){
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
          $leavetype->hours_value = 8;  // default
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
            // reverse the cuti
            $curcuti->reverseCuti();
            // then delete the cuti
            $curcuti->delete();
            $c->load_status = 'S';
          } else {
            // most likely duplicate. ignore
            $c->load_status = 'D';
          }
        } else {
          // not exist. create new
          if($c->status == 'REJECTED' || $c->status == 'WITHDRAWN'){
            // no need to create new for this one lol
            $c->load_status = 'I';

          } else {
            $curcuti = new StaffLeave;
            $curcuti->user_id = $user->id;
            $curcuti->start_date = $c->date_start;
            $curcuti->end_date = $c->date_end;
            $curcuti->leave_type_id = $leavetype->id;
            $curcuti->save();

            $curcuti->createCuti();
            $c->load_status = 'S';
          }
        }
      } else {
        $c->load_status = 'U';
      }

      $c->save();
    }
  }

  public static function loadOMData(){
    $omdata = SapEmpProfile::where('load_status', 'N')->get();

    foreach ($omdata as $key => $value) {
      // find this user
      $user = User::where('persno', $value->personel_no)->first();
      if($user){

      } else {
        // user not found. try to find using staffno instead
        $usermap = StaffPersMap::where('persno', $value->personel_no)->first();
        if($usermap){
          $user = User::where('staff_no', $usermap->staff_no)->first();
          if($user){

          } else {
            // user still not found.

            if($value->status == 'Inactive'){
              // skip if for inactive
              continue;
            }

            // create new
            $user = new User;
            $user->staff_no = $usermap->staff_no;
            $user->persno = $value->personel_no;
            $user->isvendor = false;
          }
        } else {
          // user not found. skip
          continue;
        }
      }

      if($value->status == 'Inactive'){
        // skip if for inactive
        $user->status = 0;
        $user->save();
        $value->load_status = 'D';
        $value->save();
        continue;
      }

      // begin update the data
      $user->name = $value->name;
      $user->cost_center = $value->cost_center;
      $user->position = $value->position_name;
      $user->lob = $value->group_no;
      $user->report_to = $value->reportingto_no;

      // find the division
      $unit = Unit::where('pporgunit', $value->group_no)->first();
      if($unit){

      } else {
        $unit = new Unit;
        $unit->lob = 3000;
        $unit->pporgunit = $value->group_no;
      }

      $unit->pporgunitdesc = $value->group_name;
      $unit->save();
      $user->unit_id = $unit->id;

      // then the subdiv
      $subu = SubUnit::where('ppsuborg', $value->unit_no)->first();
      if($subu){
      } else {
        $subu = new SubUnit;
        $subu->lob = 3000;
        $subu->ppsuborg = $value->unit_no;
      }

      $subu->ppsuborgunitdesc = $value->unit_name;
      $subu->pporgunit = $value->group_no;
      $subu->pporgunitdesc = $value->group_name;
      $subu->save();

      $user->unit = $value->group_name;
      $user->subunit = $value->unit_name;

      $user->save();
      $value->load_status = 'D';
      $value->save();

    }
  }

}
