<?php

namespace App\common;

use Illuminate\Http\Request;
use App\User;
use App\GwdActivity;
use \Carbon\Carbon;
use App\ActivityType;
use App\TaskCategory;
use App\Avatar;
use App\DailyPerformance;
use App\PublicHoliday;
use \DB;

class GDWActions
{
  public static function addActivity(Request $req, $staff_id){

    if($req->filled('actdate')){
      $indate = $req->actdate;
    } else {
      $indate = date('Y-m-d');
    }

    $dp = GDWActions::GetDailyPerfObj($staff_id, $indate);

    $act = new GwdActivity;
    $act->user_id = $staff_id;

    // get the TaskCategory
    $taskcat = TaskCategory::where('descr', $req->actcat)->orderBy('status', 'DESC')->orderBy('created_at', 'DESC')->first();
    if($taskcat){
      $taskcat_id = $taskcat->id;
    } else {
      $taskcat_id = 0;
    }

    // get the activity type
    $actttype = ActivityType::where('descr', $req->acttype)->orderBy('status', 'DESC')->orderBy('created_at', 'DESC')->first();
    if($actttype){
      $actttype_id = $actttype->id;
    } else {
      $actttype_id = 0;
    }

    $act->activity_type_id = $actttype_id;
    $act->task_category_id = $taskcat_id;
    // $act->title = $req->title;

    $act->hours_spent = $req->hours;

    // optionals
    if($req->filled('title')){
      $act->title = $req->title;
    } else {
      $act->title = 'd';
    }

    if($req->filled('parent_no')){
      $act->parent_number = $req->parent_no;
    }

    if($req->filled('details')){
      $act->details = $req->details;
    }

    $act->activity_date = $indate;
    $user = $act->User;

    if($user->isvendor == 1){
      $act->partner_id = $user->partner_id;
    } else {
      $act->unit_id = $user->unit_id;
    }

    // get current checkin as well
    $act->checkin_id = $user->curr_checkin;

    // tie to daily perf
    $act->daily_performance_id = $dp->id;
    $act->save();

    // update the daily perf hours
    $dp->addHours($req->hours);

    GDWActions::updateAvatar($staff_id);

    return $act;

  }

  public static function editActivity(Request $req){

    $act = GwdActivity::find($req->id);
    if(!$act){
      return "404";
    }

    $currdp = $act->DailyPerf;

    $act->activity_type_id = $req->acttype;
    $act->task_category_id = $req->actcat;




    if($req->filled('actdate')){
      $act->activity_date = $req->actdate;

      // day changed
      $nudp = GDWActions::GetDailyPerfObj($act->user_id, $req->actdate);
      $nudp->addHours($act->hours_spent);
      $act->daily_performance_id = $nudp->id;

      // reduce the hours from the old daily perf
      $currdp->addHours(0 - $act->hours_spent);

    } else {
      // get the diff in hours
      $hoursdiff = $req->hours - $act->hours_spent;
      $currdp->addHours($hoursdiff);
    }

    $act->hours_spent = $req->hours;

    // optionals
    if($req->filled('title')){
      $act->title = $req->title;
    }

    if($req->filled('parent_no')){
      $act->parent_number = $req->parent_no;
    }

    if($req->filled('details')){
      $act->details = $req->details;
    }

    $act->save();

    GDWActions::updateAvatar($act->user_id);

    return $act;

  }

  public static function deleteActivity($activityid){
    $act = GwdActivity::find($activityid);
    if(!$act){
      return "404";
    }

    // take out the hours from daily perf
    $currdp = $act->DailyPerf;
    $currdp->addHours(0 - $act->hours_spent);

    $staffid = $act->user_id;
    $act->delete();
    GDWActions::updateAvatar($staffid);

    return "deleted";
  }

  public static function updateAvatar($staff_id){
    $curdate = date('Y-m-d');

    $curhours = GwdActivity::where('user_id', $staff_id)
      ->whereDate('activity_date', $curdate)
      ->sum('hours_spent')
      // ->get()
      ;


    $av = Avatar::where('min_hours', '<=', $curhours)
      ->where('max_hours', '>=', $curhours)
      ->orderBy('max_hours', 'DESC')
      ->first();

    $rank = 0;
    if($av){
      $rank = $av->rank;
    }

    $user = User::find($staff_id);
    $user->avatar_rank = $rank;
    $user->save();
    $avta = $user->Avatar ;
    $avta->curr_total_hours = $curhours;
    return $avta;
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

  public static function getActInfoOnDate($staff_id, $date, $startrnd){
    $label = [];
    $data = [];
    $bgs = [];
    // get the list of act type
    $actype = ActivityType::where('status', 1)->get();
    $counter = $startrnd;
    foreach ($actype as $key => $value) {
      $counter++;
      array_push($label, $value->descr);
      array_push($bgs, GDWActions::getBgColor($counter));
      // get the sum of hours for this activity type
      $hrs = GwdActivity::where('user_id', $staff_id)
        ->where('activity_type_id', $value->id)
        ->whereDate('activity_date', '=', $date)
        ->sum('hours_spent');
      array_push($data, $hrs);
    }

    $info = [
      'label' => $label,
      'data' => $data,
      'bg' => $bgs
    ];

    // dd($info);
    return $info;

  }

  public static function getGwdActivities($staff_id, $date){
    $cdate = Carbon::parse($date);
    $sdate = $cdate->startOfMonth()->toDateString();
    $edate = $cdate->addMonths(1)->toDateString();

    $actlist = GwdActivity::where('user_id', $staff_id)
      ->whereDate('activity_date', '>=', $sdate)
      ->whereDate('activity_date', '<', $edate)
      ->get();

    return $actlist;
  }



  public static function getBgColor($i){
    $bgcolors = [
      'rgba(255, 99, 132, 0.8)',
      'rgba(255, 150, 5, 0.5)',
      'rgba(0, 255, 132, 0.5)',
      'rgba(0, 0, 255, 0.5)',
      'rgba(255, 0, 0, 0.5)',
      'rgba(0, 0, 0, 0.8)',
      'rgba(14, 170, 132, 0.5)',
      'rgba(108, 68, 229, 0.7)',
      'rgba(215, 215, 44, 0.8)',
      'rgba(255, 0, 255, 0.8)',
      'rgba(24, 38, 6, 0.8)',
    ];

    return $bgcolors[$i % count($bgcolors)];
  }

  public static function setOnLeave($staff_id, $date, $remark){

    // find if there is any existing leave on that day
    $act = GwdActivity::where('user_id', $staff_id)
      ->whereDate('activity_date', $date)
      ->where('isleave', true)
      ->first();

    if($act){

    } else {
      $act = new GwdActivity;
      $act->user_id = $staff_id;
      $act->isleave = true;
      $act->activity_date = $date;
      $act->activity_type_id = 0;
      $act->title = 'cuti';
      $act->hours_spent = 0;

      $user = $act->User;

      if($user->isvendor == 1){
        $act->partner_id = $user->partner_id;
      } else {
        $act->unit_id = $user->unit_id;
      }

    }

    $act->leave_remark = $remark;
    $act->save();

    return $act;

  }

  public static function GetExpectedHours($date, DailyPerformance $dpp = null, $exclude = null){
    // first, check if it's a public holiday
    if($exclude){
      $ph = PublicHoliday::whereDate('event_date', $date)
        ->where('id', '!=', $exclude)->first();
    } else {
      $ph = PublicHoliday::whereDate('event_date', $date)->first();
    }


    if($ph){

      if($dpp){
        $dpp->is_public_holiday = true;
        $dpp->public_holiday_id = $ph->id;
      }

      return 0;
    }

    // not a public holiday. check day of the week
    $carbond = new Carbon($date);
    $dow = $carbond->dayOfWeekIso;

    if($dow == 5){
      return 7.5;
    } elseif($dow > 5){
      return 0;
    } else {
      return 8;
    }

  }

  public static function GetDailyPerfObj($user_id, $date){
    $dp = DailyPerformance::where('user_id', $user_id)
      ->whereDate('record_date', $date)
      ->first();

    if($dp){

    } else {
      // no record. create new
      $user = User::find($user_id);
      $dp = new DailyPerformance;
      $dp->user_id = $user_id;
      $dp->record_date = $date;
      $dp->unit_id = $user->lob;
      $dp->expected_hours = GDWActions::GetExpectedHours($date, $dp);
      $dp->save();
    }

    return $dp;
  }

}
