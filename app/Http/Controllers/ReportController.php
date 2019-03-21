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
      } elseif ($onedate->format('w') == 5) {
        // friday
        $isweken = 'n';
        $totExptHours += 7.5;
      }
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
      orderBy('name', 'ASC')->get();
    // return $field;

    // so, for each staff
    foreach($allstaffs as $astaff){
      $sdata = [];
      $stotal = 0;
      array_push($sdata, $astaff->name);
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
      array_push($finalout, $sdata);

    }

    return view('report.workhourr', [
      'rlabel' => $searchlabel,
      'header' => $dateheader,
      'staffs' => $finalout,
      'expthours' => $totExptHours
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

}
