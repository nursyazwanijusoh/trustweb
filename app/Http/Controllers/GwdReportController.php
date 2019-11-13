<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DB;
use App\Unit;
use App\CompGroup;
use \Carbon\Carbon;
use App\common\GDWReports;

class GwdReportController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index(){

  }

  // show the overview summary, by month
  public function summary(Request $req){

    $grplist = CompGroup::all();
    $curdate = date('Y-m-d');
    $lastweek = date('Y-m-d', strtotime('-1 week'));


    return view('report.rptgrpsummary', [
      'glist' => $grplist,
      'sdate' => $lastweek,
      'edate' => $curdate
    ]);
  }

  public function summaryres(Request $req){

  }

  // maybe to show who didnt key in diaries
  public function entrystat(Request $req){
    dd("under development");
  }

  public function entrystatres(Request $req){

  }

  // detailed report, similar like current gwd
  public function detail(Request $req){

    // if actually pressed submit
    if($req->filled('subtype')){
      if($req->subtype == 'lob'){
        $svalue = $req->pporgunit;
      } else {
        $svalue = $req->subunit;
      }
      // return $req;
      $redata = GDWReports::getWorkdaysResult($req->subtype, $svalue, $req->fdate, $req->todate, $req->pporgunit);

      return view('gwd.detailr', $redata);
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

    return view('gwd.detailf', [
      'divlist' => $divalist,
      'unitlist' => $unitlist,
      'curdate' => $curdate,
      'fromdate' => $minus7days,
      'gotunit' => $unitbtn
    ]);
  }

  public function detailres(Request $req){

  }

  public function doGrpSummary(Request $req){

    $cgrp = CompGroup::find($req->gid);
    if($cgrp){

    } else {
      return redirect()->back()->withInput()->withErrors(['gid' => 'Selected group no longer exist']);
    }

    // date validation?


    $daterange = new DatePeriod(
      new DateTime($fdate),
      DateInterval::createFromDateString('1 day'),
      new DateTime($todate)
    );

    foreach ($cgrp->Members as $onemember) {
      $staffcount = $onemember->Staffs->count();

      foreach ($daterange as $adate) {
        $dperfs = $onemember->PerfEntryOnDate($adate);

        // $sumExp = 

      }


    }









    $grplist = CompGroup::all();
    foreach ($grplist as $key => $value) {
      if($value->id == $req->gid){
        $value->selected = 'selected';
      }
    }

    $curdate = $req->tdate;
    $lastweek = $req->fdate;

    return view('report.rptgrpsummary', [
      'glist' => $grplist,
      'sdate' => $lastweek,
      'edate' => $curdate
    ]);

  }

}
