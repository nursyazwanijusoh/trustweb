<?php

namespace App\common;

use App\Api\V1\Controllers\BookingHelper;
use App\Checkin;
use App\reservation;
use App\User;
use App\place;
use \DB;

class HDReportHandler
{

  public function getDivByDate($lob, $rptdate){

    $bh = new BookingHelper;

    $data = User::where('lob', $lob)->orderBy('subunit', 'ASC')->get();

    foreach ($data as $auser) {
      // get the checkin info for this user on this date
      $mycheckin = Checkin::where('user_id', $auser->id)
        ->whereDate('checkin_time', $rptdate)
        ->orderBy('checkin_time', 'ASC')->get();

      $cklist = [];

      foreach ($mycheckin as $oneck) {
        array_push($cklist, $bh->getCheckinInfo($oneck->id));
      }

      if(empty($cklist)){
        $auser->checkins = 'empty';
      } else {
        $auser->checkins = $cklist;
      }
    }

    return $data;
  }

  public function getFloorUsage($buildid){
    $bh = new BookingHelper;
    $occupied = 0;
    $free = 0;

    // first get the occupied seats
    $occseats = place::where('building_id', $buildid)
      ->where('status', '>', '1')->get();

    foreach($occseats as $key => $oseat){

      if($oseat->status == 2){
        $staff_id = $oseat->reserve_staff_id;
        $type = 'Reserve';
      } else {
        // 3 - checkins
        $staff_id = $oseat->checkin_staff_id;
        $type = 'Check-In';
      }

      if(isset($staff_id)){
        // find who checked in here
        $cuser = User::find($oseat->checkin_staff_id);

        // then get that checkin detail
        if($oseat->status == 2){
          $cdetail = reservation::find($cuser->curr_reserve);
          $oseat->cin_time = $cdetail->expire_time;
        } else {
          // 3 - checkins
          $cdetail = Checkin::find($cuser->curr_checkin);
          $oseat->cin_time = $cdetail->checkin_time;
        }


        // plug in the data
        $oseat->staff_no = $cuser->staff_no;
        $oseat->name = $cuser->name;
        $oseat->type = $type;
        $occupied++;
      } else {
        // no valid staff id tied to the correct type. reset the status
        $oseat->status = 1;
        $oseat->save;
        // remove this seat from occupied list
        unset($occseats[$key]);
      }
    }

    // next, get the free seats
    // $feeseats = place::where('building_id', $buildid)
    //   ->where('status', '1')
    //   ->where('seat_type', 1)->get();
    //
    // $free = $feeseats->count();

    // get the floor building name
    $thebuild = $bh->getBuildingInfo($buildid);

    // then, get the meeting areas
    $marea = place::where('building_id', $buildid)
      ->where('status', '1')
      ->where('seat_type', 2)->get();

    foreach ($marea as $key => $value) {
      if($value->Checkin->count() == 0){
        // remove meeting area with  no occupant
        unset($marea[$key]);
      }
    }

    return [
      'occupied' => $occseats
      , 'meetarea' => $marea
      , 'buildname' => $thebuild->floor_name . '@' . $thebuild->building_name
      // , 'freecount' => $free
      , 'occcount' => $occupied
    ];

  }

  public function findStaff($input){
    // first find as exact staff no
    $user = User::where('staff_no', trim($input))->get();
    // $count = 'no';

    if($user->count() == 0){
      $user = User::where('name', 'LIKE', "%${input}%")->orderBy('unit', 'ASC')->get();
      // $count = 'name';
    }


    // return ['type' => $count, 'data' => $user];
    // dd($user);
    return $user;
  }

}
