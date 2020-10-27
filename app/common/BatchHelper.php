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
          if($c->operation == 'INS' && ($c->status == 'REJECTED' || $c->status == 'WITHDRAWN')){
            // reverse the cuti
            $curcuti->reverseCuti();
            // then delete the cuti
            // $curcuti->delete();

            // delete semua cuti untuk tarikh tu. cater cases multi lines
            $curcuti2 = StaffLeave::where('leave_type_id', $leavetype->id)
              ->whereDate('start_date', $c->date_start)
              ->whereDate('end_date', $c->date_end)
              ->where('user_id', $user->id)
              ->delete();

            $c->load_status = 'S';
          } elseif($c->operation == 'DEL' && ($c->status == 'POSTED' || $c->status == 'WITHDRAWN')){
            // reverse the cuti
            $curcuti->reverseCuti();
            // then delete the cuti
            // $curcuti->delete();

            // delete semua cuti untuk tarikh tu. cater cases multi lines
            $curcuti2 = StaffLeave::where('leave_type_id', $leavetype->id)
              ->whereDate('start_date', $c->date_start)
              ->whereDate('end_date', $c->date_end)
              ->where('user_id', $user->id)
              ->delete();

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

          } elseif($c->operation == 'DEL'){
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
        // user already exist. use it
      } else {
        // no existing user with that persno. try to check if the staff_no is provided
        if(isset($value->staff_id) && $value->staff_id != 0){
          $trimmedstaffno = str_replace(' ', '', $value->staff_id);
          $user = User::where('staff_no', $trimmedstaffno)->first();
          if($user){
            // user found. use it
          } else {
            if($value->status == 'Inactive' || $value->status == 'Withdrawn'){
              // skip for inactive
              $value->load_status = 'D';
              $value->save();
              continue;
            }

            // not exist. create new
            $user = new User;
            $user->staff_no = $trimmedstaffno;
            $user->persno = $value->personel_no;
            $user->isvendor = false;
          }
        } else {
          // try to find the staff_no - persno mapping in the mapping table
          $usermap = StaffPersMap::where('persno', $value->personel_no)->first();
          if($usermap){
            $user = User::where('staff_no', $usermap->staff_no)->first();
            if($user){

            } else {
              // user still not found.

              if($value->status == 'Inactive' || $value->status == 'Withdrawn'){
                // skip for inactive
                $value->load_status = 'D';
                $value->save();
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
            $value->load_status = '4';
            $value->save();
            continue;
          }
        }

      }

      if($value->status == 'Inactive' || $value->status == 'Withdrawn'){
        // skip if for inactive
        $user->status = 0;
        $user->save();
        $value->load_status = 'D';
        $value->save();
        continue;
      }

      $gp_no = isset($value->group_no) ? $value->group_no : '0';
      $un_no = isset($value->unit_no) ? $value->unit_no : '0';

      // begin update the data
      $user->name = $value->name;
      $user->cost_center = $value->cost_center;
      $user->position = $value->postion_name;
      $user->lob = $gp_no;
      $user->report_to = $value->reportingto_no;
      $user->status = 1;

      // find the division
      $unit = Unit::where('pporgunit', $gp_no)->first();
      if($unit){

      } else {
        $unit = new Unit;
        $unit->lob = 3000;
        $unit->pporgunit = $gp_no;
      }
      $grpname = isset($value->group_name) ? $value->group_name : $gp_no;

      $unit->pporgunitdesc = $grpname;
      $unit->save();
      $user->unit_id = $unit->id;

      // then the subdiv
      $subu = SubUnit::where('ppsuborg', $un_no)->first();
      if($subu){
      } else {
        $subu = new SubUnit;
        $subu->lob = 3000;
        $subu->ppsuborg = $un_no;
      }

      $unitname = isset($value->unit_name) ? $value->unit_name : $un_no;


      $subu->ppsuborgunitdesc = $unitname;
      $subu->pporgunit = $gp_no;
      $subu->pporgunitdesc = $grpname;
      $subu->save();

      $user->unit = $grpname;
      $user->subunit = $unitname;
      // $user->jobtype = $value->postion_name;

      $user->save();
      $value->load_status = 'D';
      $value->save();

    }
  }

}
