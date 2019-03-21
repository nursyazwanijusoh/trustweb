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
      if($req->subtype == 'pporgunit'){
        $svalue = $req->pporgunit;
      } else {
        $svalue = $req->subunit;
      }

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


    $datelist = [];
    // first, get the list of date that we need to check
    $daterange = new DatePeriod(
      new DateTime($fdate),
      DateInterval::createFromDateString('1 day'),
      new DateTime($todate)
    );


    // then for each day,
    foreach($daterange as $onedate){
      array_push($datelist, $onedate->format('Y-m-d'));




    }
    return $datelist;
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
