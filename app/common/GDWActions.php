<?php

namespace App\common;

use Illuminate\Http\Request;
use App\User;
use App\GwdActivity;
use \Carbon\Carbon;
use App\ActivityType;
use \DB;

class GDWActions
{
  public static function addActivity(Request $req, $staff_id){

    $act = new GwdActivity;
    $act->user_id = $staff_id;
    $act->activity_type_id = $req->acttype;
    $act->task_category_id = $req->actcat;
    // $act->title = $req->title;
    $act->title = 'd';
    $act->hours_spent = $req->hours;

    // optionals
    if($req->filled('parent_no')){
      $act->parent_number = $req->parent_no;
    }

    if($req->filled('details')){
      $act->details = $req->details;
    }

    if($req->filled('actdate')){
      $act->activity_date = $req->actdate;
    } else {
      $act->activity_date = date('Y-m-d');
    }

    $user = $act->User;

    if($user->isvendor == 1){
      $act->partner_id = $user->partner_id;
    } else {
      $act->unit_id = $user->unit_id;
    }

    // get current checkin as well
    $act->checkin_id = $user->curr_checkin;
    $act->save();

    return $act;

  }

  public static function updateAvatar($staff_id){
    $curdate = date('Y-m-d');


  }

  public static function getActSummary($staff_id, $date){
    // get the month range from the given date
    $cdate = Carbon::parse($date);
    $monmon = $cdate->format('F Y');
    $sdate = $cdate->startOfMonth()->toDateString();
    $edate = $cdate->addMonths(1)->toDateString();
    $label = [];
    $data = [];
    $bgs = [];


    // get the list of act type
    $actype = ActivityType::where('status', 1)->get();
    $counter = rand(0, 12);
    foreach ($actype as $key => $value) {
      $counter++;
      array_push($label, $value->descr);
      array_push($bgs, GDWActions::getBgColor($counter));
      // get the sum of hours for this activity type
      $hrs = GwdActivity::where('user_id', $staff_id)
        ->where('activity_type_id', $value->id)
        ->whereDate('activity_date', '>=', $sdate)
        ->whereDate('activity_date', '<', $edate)
        ->sum('hours_spent');
      array_push($data, $hrs);
    }

    $info = [
      'label' => $label,
      'data' => $data,
      'bg' => $bgs,
      'month' => $monmon
    ];

    // dd($info);
    return $info;

  }

  public static function getBgColor($i){
    $bgcolors = [
      'rgba(255, 99, 132, 0.5)',
      'rgba(255, 150, 5, 0.5)',
      'rgba(0, 255, 132, 0.5)',
      'rgba(0, 0, 255, 0.5)',
      'rgba(100, 114, 104, 0.5)',
      'rgba(255, 0, 0, 0.5)',
      'rgba(0, 0, 0, 0.5)',
      'rgba(14, 170, 132, 0.5)',
      'rgba(108, 68, 229, 0.5)',
      'rgba(215, 215, 44, 0.5)',
      'rgba(255, 0, 255, 0.5)',
      'rgba(24, 38, 6, 0.5)',
      'rgba(255,255,255, 0.5)',
    ];

    return $bgcolors[$i % count($bgcolors)];
  }

}
