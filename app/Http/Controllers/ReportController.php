<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DB;
use \DateTime;
use \DateInterval;
use \DatePeriod;
use App\User;
use App\Unit;
use App\SubUnit;
use App\ActivityType;
use App\Charts\RegStatChart;

class ReportController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index(){
    return view('report.index');
  }

  public function registeredUser(){

    // get some summary
    $unitlist = \DB::table('users')
      ->select('lob', \DB::raw('count(*) as total'))
      // ->where('lob', \Session::get('staffdata')['lob'])
      ->groupBy('lob')->get();

    $label = [];
    $value = [];
    foreach($unitlist as $aunit){
      // get the actual name of the div
      $unit = Unit::where('pporgunit', $aunit->lob)->first();
      $unitname = $aunit->lob;
      if($unit){
        $unitname = $unit->pporgunitdesc;
      }
      array_push($label, $unitname);
      array_push($value, $aunit->total);
    }

    $schart = new RegStatChart;
    $schart->labels($label);
    $schart->dataset('Registered user', 'horizontalBar', $value);

    return view('report.regstat', ['chart' => $schart]);
  }

  public function manDaysDispf(Request $req){

    // if actually pressed submit
    if($req->filled('subtype')){
      if($req->subtype == 'lob'){
        $svalue = $req->pporgunit;
      } else {
        $svalue = $req->subunit;
      }
      // return $req;
      return $this->getWorkdaysResult($req->subtype, $svalue, $req->fdate, $req->todate);
    }

    // else, just initialize the search form
    // default date
    $curdate = date('Y-m-d');
    $minus7days = date('Y-m-d', strtotime('-1 week'));

    if($req->filled('fdate')){
      $minus7days = $req->fdate;
    }

    if($req->filled('todate')){
      $curdate = $req->todate;
    }

    // get the registered list of division / unit
    $divrlist = DB::table('users')
      ->select('lob', DB::raw('count(*) as reg_count'))
      ->groupBy('lob')
      ->get();

    $seldiv = '';
    $unitbtn = 'd-none';
    $unitlist = [];
    $divalist = [];

    if($req->filled('pporgunit')){
      $seldiv = $req->pporgunit;

      // get list of subunit under this unit/div

      $unitlist = DB::table('users')
        ->where('lob', $seldiv)
        ->select('subunit', DB::raw('count(*) as reg_count'))
        ->groupBy('subunit')
        ->get();
      $unitbtn = '';
    }

    // translate the div/unit
    foreach($divrlist as $adiv){
      $sel = '';
      $unit = Unit::where('pporgunit', $adiv->lob)->first();
      $unitname = $adiv->lob;  // default, just in case
      if($unit){
        $unitname = $unit->pporgunitdesc;
      }

      if($adiv->lob == $seldiv){
        $sel = 'selected';
      }

      array_push($divalist, [
        'pporgunit' => $adiv->lob,
        'divname' => $unitname,
        'regcount' => $adiv->reg_count,
        'sel' => $sel
      ]);
    }

    return view('report.workhourf', [
      'divlist' => $divalist,
      'unitlist' => $unitlist,
      'curdate' => $curdate,
      'fromdate' => $minus7days,
      'gotunit' => $unitbtn
    ]);

  }

  private function getWorkdaysResult($field, $svalue, $fdate, $todate){

    $searchlabel = $svalue;
    $totExptHours = 0;
    if($field == 'lob'){
      $unitv = Unit::where('pporgunit', $svalue)->first();
      if($unitv){
        $searchlabel = $unitv->pporgunitdesc;
      }
    }

    $finalout = [];
    $dateheader = [];
    array_push($dateheader, ['date' => 'Staff Name', 'isweekend' => 'y']);
    // first, get the list of date that we need to check
    $daterange = new DatePeriod(
      new DateTime($fdate),
      DateInterval::createFromDateString('1 day'),
      new DateTime($todate)
    );

    foreach($daterange as $onedate){
      if($onedate->format('w') == 0 || $onedate->format('w') == 6){
        $isweken = 'y';
      }
      // elseif ($onedate->format('w') == 5) {
      //   // friday
      //   $isweken = 'n';
      //   $totExptHours += 7.5;
      // }
      else {
        $isweken = 'n';
        $totExptHours += 8;
      }

      $d = [
        'date' => $onedate->format('m-d'),
        'isweekend' => $isweken
      ];

      array_push($dateheader, $d);
    }
    array_push($dateheader, ['date' => 'Total Hours', 'isweekend' => 'y']);

    // next, get the list of staff under the selection criteria
    $allstaffs = User::where($field, $svalue)
      ->orderBy('name', 'ASC')->get();
    // return $field;

    // so, for each staff
    foreach($allstaffs as $astaff){
      $sdata = [];
      $stotal = 0;
      // array_push($sdata, $astaff->name);
      // and for each day,
      foreach($daterange as $onedate){
        $dsum = DB::table('activities')
          ->join('tasks', 'tasks.id', '=', 'activities.task_id')
          ->where('tasks.user_id', $astaff->id)
          ->where('activities.date', $onedate->format('Y-m-d'))
          ->sum('activities.hours_spent');
        array_push($sdata, $dsum);
        $stotal += $dsum;
      }
      array_push($sdata, $stotal);

      $onestaffdata = [
        'name' => $astaff->name,
        'staff_no' => $astaff->staff_no,
        'hours' => $sdata
      ];

      array_push($finalout, $onestaffdata);

    }

    return view('report.workhourr', [
      'rlabel' => $searchlabel,
      'header' => $dateheader,
      'staffs' => $finalout,
      'expthours' => $totExptHours,
      'fromdate' => $fdate,
      'todate' => $todate
    ]);

  }

  public function showDepts(){
    $mylob = 3000;

    $unitlist = Unit::where('lob', $mylob)->get();
    foreach($unitlist as $aunit){
      $subunitlist = SubUnit::where('lob', $mylob)->where('pporgunit', $aunit->pporgunit)->get();
      $aunit['subunit'] = $subunitlist;
    }

    return view('report.deptlov', ['deptid' => $mylob, 'units' => $unitlist]);
  }

  public function staffDayRptSearch(Request $req){
    $input = app('request')->all();

    $rules = [
      'staff_no' => ['required']
    ];

    $validator = app('validator')->make($input, $rules);
    if($validator->fails()){
      return response('staff_no is required', 413, $input);
    }

    //date inputs
    $curdate = date('Y-m-d');
    $minus7days = date('Y-m-d', strtotime('-1 week'));
    if($req->filled('todate')){
      $curdate = date('Y-m-d', strtotime($req->todate));

      if($req->filled('fromdate')){
        $minus7days = date('Y-m-d', strtotime($req->fromdate));
      } else {
        $minus7days = date('Y-m-d', strtotime('-1 week', strtotime($req->todate)));
      }

    }

    // get the user id from staff_no
    $staffdata = User::where('staff_no', $req->staff_no)->first();

    if($staffdata){

      // containers
      $typelist = [];
      $typeHeader = [];
      array_push($typeHeader, 'Date');
      $typeFooter = [];
      array_push($typeFooter, 'Total');
      $datalist = [];
      $tothrs = 0;

      // get distinct activity types within those date
      $typelistdb = DB::table('activities')
        ->join('tasks', 'tasks.id', '=', 'activities.task_id')
        ->where('tasks.user_id', $staffdata->id)
        ->whereBetween('activities.date', array($minus7days, $curdate))
        ->select('activities.act_type', DB::raw('sum(activities.hours_spent) as tot_hrs'))
        ->groupBy('activities.act_type')
        ->get();
      // then load it into the container
      foreach($typelistdb as $tl){
        // get the name
        $actype = ActivityType::find($tl->act_type);
        $actname = 'Type Removed';
        if($actype){
          $actname = $actype->descr;
        }

        array_push($typelist, $tl->act_type);
        array_push($typeHeader, $actname);
        array_push($typeFooter, $tl->tot_hrs);
        $tothrs += ($tl->tot_hrs * 10);
      }

      array_push($typeHeader, 'Total');
      array_push($typeFooter, ($tothrs / 10));

      // then, get distinct days
      $daylistdb = DB::table('activities')
        ->join('tasks', 'tasks.id', '=', 'activities.task_id')
        ->where('tasks.user_id', $staffdata->id)
        ->whereBetween('activities.date', array($minus7days, $curdate))
        ->select('activities.date', DB::raw('sum(activities.hours_spent) as tot_hrs'))
        ->groupBy('activities.date')
        ->orderBy('activities.date', 'ASC')
        ->get();

      // dd($daylistdb);

      foreach($daylistdb as $aday){
        $sdata = [];
        array_push($sdata, date('D d-m', strtotime($aday->date)));
        // get the sum hours for each type
        foreach($typelist as $tl){
          $dsum = DB::table('activities')
            ->join('tasks', 'tasks.id', '=', 'activities.task_id')
            ->where('tasks.user_id', $staffdata->id)
            ->where('activities.act_type', $tl)
            ->where('activities.date', $aday->date)
            ->sum('activities.hours_spent');
          array_push($sdata, $dsum);

        }
        array_push($sdata, $aday->tot_hrs);
        array_push($datalist, $sdata);

      }


      array_push($datalist, $typeFooter);

      return view('report.userbyday', [
        'header' => $typeHeader,
        'data' => $datalist,
        'name' => $staffdata->name,
        'fromdate' => $minus7days,
        'todate' => $curdate
      ]);
    } else {
      dd('Staff Not Registered: ' . $req->staff_no);
    }
  }

  public function staffSpecificDayRptSearch(Request $req){
    $input = app('request')->all();

    $rules = [
      'staff_no' => ['required']
    ];

    $validator = app('validator')->make($input, $rules);
    if($validator->fails()){
      return response('staff_no is required', 413, $input);
    }

    //date inputs
    $curdate = date('Y-m-d');
    if($req->filled('date')){
      $curdate = date('Y-m-d', strtotime($req->date));
    }

    $staffdata = User::where('staff_no', $req->staff_no)->first();

    if($staffdata){

      // get the activities for that date
      $activit = DB::table('activities')
        ->join('tasks', 'tasks.id', '=', 'activities.task_id')
        ->where('tasks.user_id', $staffdata->id)
        ->where('activities.date',  $curdate)
        ->select('activities.remark', 'tasks.id', 'tasks.name', 'activities.hours_spent')
        ->orderBy('tasks.name', 'ASC')
        ->get();

      return view('report.userspecificday', [
        'name' => $staffdata->name,
        'date' => $req->date,
        'data' => $activit
      ]);
    } else {
      dd('Staff Not Registered: ' . $req->staff_no);
    }

  }

}
