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

    $curdate = $req->tdate;
    $lastweek = $req->fdate;
    $cgrp = CompGroup::find($req->gid);
    if($cgrp){

    } else {
      return redirect()->back()->withInput()->withErrors(['gid' => 'Selected group no longer exist']);
    }

    // date validation?



    // $daterange = new DatePeriod(
    //   new DateTime($fdate),
    //   DateInterval::createFromDateString('1 day'),
    //   new DateTime($todate)
    // );

    $lbl = [];
    $tierA = [];
    $tierB = [];
    $tierC = [];
    $tierD = [];

    foreach ($cgrp->Members as $onemember) {
      $ca = 0;
      $cb = 0;
      $cc = 0;
      $cd = 0;
      // $allstaff = $onemember->Staffs;
      // $staffcount = $allstaff->count();
      array_push($lbl, $onemember->pporgunitdesc);

      $allrec = $onemember->PerfEntryOnDateRange($lastweek, $curdate);

      $perstaff = $allrec->select(
          'user_id',
          DB::raw('sum(expected_hours) as exp_hrs'),
          DB::raw('sum(actual_hours) as act_hrs')
        )->groupBy('user_id')
        ->get();

      foreach ($perstaff as $maybeonestaff) {

        if($maybeonestaff->exp_hrs == 0){
          if($maybeonestaff->act_hrs == 0){
            $cc++;
          } else {
            $cd++;
          }

        } else {
          $pers = $maybeonestaff->act_hrs / $maybeonestaff->exp_hrs * 100;
          if($pers < 50){
            $ca++;
          } elseif ($pers < 80) {
            $cb++;
          } elseif ($pers <= 100) {
            $cc++;
          } else {
            $cd++;
          }
        }

      }

      array_push($tierA, $ca);
      array_push($tierB, $cb);
      array_push($tierC, $cc);
      array_push($tierD, $cd);
    }

    // dd($lbl);

    $datasets = array([
          'label' => '< 50%',
          'data' => $tierA,
          'backgroundColor' => "rgba(255, 255, 0, 0.5)",
          'borderColor' => "rgba(255, 199, 0, 0.7)",
        ],
        [
          'label' => '50% - 80%',
          'data' => $tierB,
          'backgroundColor' => "rgba(51, 51, 204, 0.5)",
          'borderColor' => "rgba(51, 51, 204, 0.7)",
        ],
        [
          'label' => '80% - 100%',
          'data' => $tierC,
          'backgroundColor' => 'rgba(51, 204, 51, 0.5)',
          'borderColor' => 'rgba(51, 204, 51, 0.7)',
        ],
        [
          'label' => '> 100%',
          'data' => $tierD,
          'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
          'borderColor' => 'rgba(255, 40, 132, 0.7)',
        ],
      );


    $grplist = CompGroup::all();
    foreach ($grplist as $key => $value) {
      if($value->id == $req->gid){
        $value->selected = 'selected';
      }
    }


    return view('report.rptgrpsummary', [
      'glist' => $grplist,
      'sdate' => $lastweek,
      'edate' => $curdate,
      'rptdata' => true,
      'sumchart' => $this->getStackBarChart($lbl, $datasets, $cgrp->name . ' performance between ' . $lastweek . ' and ' . $curdate)
    ]);

  }

  private function getStackBarChart($label, $datasets, $title){
    $schart = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->size(['width' => 400, 'height' => 250])
         ->labels($label)
         ->datasets($datasets)
         ->options([
           'responsive' => true,
           'title' => [
             'display' => true,
             'text' => $title,
           ],
           'tooltips' => [
             'mode' => 'index',
             'intersect' => false,
           ],
           'hover' => [
             'mode' => 'nearest',
             'intersect' => true,
           ],
           'scales' => [
             'xAxes' => [[
               'display' => true,
               'stacked' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Time',
               ]
             ]],
             'yAxes' => [[
               'display' => true,
               'stacked' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Seat Count',
               ]
             ]]
           ]
         ]);

    return $schart;
  }

}
