<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DB;
use App\Unit;
use App\CompGroup;
use App\DailyPerformance;
use \Carbon\Carbon;
use App\common\GDWReports;
use App\common\ExcelHandler;
use App\common\GDWActions;

class GwdReportController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index(){

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

  // show the overview summary, by month
  public function summary(Request $req){

    if($req->filled('action')){

      if(!$req->filled('fdate')){
        return redirect()->back()->withInput()->withErrors(['fdate' => 'From date is required']);
      }

      if(!$req->filled('tdate')){
        return redirect()->back()->withInput()->withErrors(['tdate' => 'To date is required']);
      }

      if($req->action == 'graph'){
        return $this->doGrpSummary($req);
      } elseif ($req->action == 'excel') {
        return $this->doGrpExcel($req);
      }
    }

    $grplist = CompGroup::all();
    $curdate = date('Y-m-d');
    $lastweek = date('Y-m-d', strtotime('-1 week'));


    return view('report.rptgrpsummary', [
      'glist' => $grplist,
      'sdate' => $lastweek,
      'edate' => $curdate
    ]);
  }

  private function doGrpExcel(Request $req){
    set_time_limit(0);
    $cdate = new Carbon($req->tdate);
    $ldate = new Carbon($req->fdate);
    $cdate->addSecond();

    $cgrp = CompGroup::find($req->gid);
    if($cgrp){
    } else {
      return redirect()->back()->withInput()->withErrors(['gid' => 'Selected group no longer exist']);
    }

    $noww = Carbon::now();

    $fname = 'diary_'
      . $ldate->format('Ymd') . '_' . $cdate->format('Ymd')
      . '_' . str_replace(' ', '', $cgrp->name)
      . '_' . $noww->format('Ymd_His') . '.xlsx';

    $headers = ['Name', 'Staff No', 'Division', 'Section', 'Email'];

    $hrsdata = [];
    $mandaydata = [];
    $dayinfo = [];

    $daterange = new \DatePeriod(
      $ldate,
      \DateInterval::createFromDateString('1 day'),
      $cdate
    );


    foreach ($daterange as $key => $value) {
      array_push($headers, $value->format('d (D)'));

      $dtype = GDWActions::GetDayType($value->format('Y-m-d'));
      $expthrs = GDWActions::GetExpectedHours($value->format('Y-m-d'));
      switch($dtype){
        case GDWActions::DT_NW:
          $bgcolor = ExcelHandler::BG_NORMAL;
          break;
        case GDWActions::DT_PH:
          $bgcolor = ExcelHandler::BG_PH;
          break;
        case GDWActions::DT_WK:
          $bgcolor = ExcelHandler::BG_WEEKEND;
          break;
        default:
          $bgcolor = ExcelHandler::BG_NORMAL;
      }

      array_push($dayinfo, [
        'date' => $value->format('Y-m-d'),
        'type' => $dtype,
        'bgcolor' => $bgcolor,
        'exhrs' => $expthrs
      ]);
    }

    // dd($dayinfo);

    array_push($headers, 'Actual');
    array_push($headers, 'Expected');

    // prep the excel handler
    $eksel = new ExcelHandler($fname);

    // get the list of divs under this group
    foreach ($cgrp->Members as $onemember) {
      foreach ($onemember->Staffs as $value) {
        $expectedentry = 0;
        $expectedmd = 0;
        $expectedhrs = 0;
        $sumentry = 0;
        $sumhrs = 0;
        $summd = 0;

        $datmd = [
          ['v' => $value->name, 't' => ExcelHandler::BG_INFO],
          ['v' => $value->staff_no, 't' => ExcelHandler::BG_INFO],
          ['v' => $onemember->pporgunitdesc, 't' => ExcelHandler::BG_INFO],
          ['v' => $value->subunit, 't' => ExcelHandler::BG_INFO],
          ['v' => $value->email, 't' => ExcelHandler::BG_INFO]
        ];

        $dathrs = [
          ['v' => $value->name, 't' => ExcelHandler::BG_INFO],
          ['v' => $value->staff_no, 't' => ExcelHandler::BG_INFO],
          ['v' => $onemember->pporgunitdesc, 't' => ExcelHandler::BG_INFO],
          ['v' => $value->subunit, 't' => ExcelHandler::BG_INFO],
          ['v' => $value->email, 't' => ExcelHandler::BG_INFO]
        ];

        foreach ($dayinfo as $odt) {
          $dpu = DailyPerformance::where('user_id', $value->id)
            ->whereDate('record_date', $odt['date'])
            ->first();

          $dbg = $odt['bgcolor'];
          $hrs = 0;

          if($dpu){
            // check for off day
            if($dpu->is_off_day){
              if($dpu->expected_hours < 5){
                $dbg = ExcelHandler::BG_LEAVE0;
              } else {
                $dbg = ExcelHandler::BG_LEAVE;
              }

            }

            if($dpu->expected_hours > 0){
              $expectedentry++;
              $expectedmd++;
              $expectedhrs += $dpu->expected_hours;

              if($dpu->actual_hours > 0){
                $sumentry++;
              }
            }

            if($dpu->actual_hours > 0){
              $sumhrs += $dpu->actual_hours;
              $hrs = $dpu->actual_hours;
            }



          } else {
            // no entry for that date. so just in case, load default values
            if($odt['type'] == GDWActions::DT_NW){
              $expectedentry++;
              $expectedmd++;
              $expectedhrs += $odt['exhrs'];
            }

          }

          $md = $hrs / ($odt['exhrs'] == 0 ? 8 : $odt['exhrs']);
          $summd += $md;
          // populate today's data
          array_push($datmd, ['v' => $md, 't' => $dbg]);
          array_push($dathrs, ['v' => $hrs, 't' => $dbg]);
        }

        // populate the final sums for md
        array_push($datmd, ['v' => $summd, 't' => ExcelHandler::BG_INFO]);
        array_push($datmd, ['v' => $expectedmd, 't' => ExcelHandler::BG_INFO]);

        // push the MD data to main array
        array_push($mandaydata, $datmd);

        // final sums for hours
        array_push($dathrs, ['v' => $sumhrs, 't' => ExcelHandler::BG_INFO]);
        array_push($dathrs, ['v' => $expectedhrs, 't' => ExcelHandler::BG_INFO]);
        array_push($dathrs, ['v' => $sumentry, 't' => ExcelHandler::BG_INFO]);
        array_push($dathrs, ['v' => $expectedentry, 't' => ExcelHandler::BG_INFO]);

        // calc the productivity
        if($expectedhrs == 0){
          if($sumhrs > 0){
            $pdtivity = $sumhrs * 100;
          } else {
            $pdtivity = 100;
          }
        } else {
          $pdtivity = $sumhrs / $expectedhrs * 100;
          if($pdtivity == 0){
            $pdgrp = '0%';
            $pbg = ExcelHandler::PD_G0;
          } elseif($pdtivity < 50){
            $pdgrp = '1% - 49%';
            $pbg = ExcelHandler::PD_GB;
          } elseif($pdtivity < 70){
            $pdgrp = '50% - 69%';
            $pbg = ExcelHandler::PD_GB;
          } elseif($pdtivity <= 100){
            $pdgrp = '70% - 100%';
            $pbg = ExcelHandler::PD_GC;
          } else {
            $pdgrp = '101% +';
            $pbg = ExcelHandler::PD_GD;
          }
        }


        array_push($dathrs, ['v' => number_format($pdtivity, 2), 't' => $pbg]);
        array_push($dathrs, ['v' => $pdgrp, 't' => ExcelHandler::BG_NORMAL]);

        // push hours to main array
        array_push($hrsdata, $dathrs);

      }
    }

    //
    // $eksel->addSheet('By Entry', [], ['t1', 't2']);
    // $eksel->addSheet('By Productivity', [], []);
    // $eksel->addSheet('By Div Productivity', [], []);
    //
    $eksel->addSheet('By Man-Days', $mandaydata, $headers);

    array_push($headers, 'Actual entry');
    array_push($headers, 'Expected entry');
    array_push($headers, 'Productivity');
    array_push($headers, 'Range');
    $eksel->addSheet('By Hours',$hrsdata, $headers);



    return $eksel->download();

  }

  private function doGrpSummary(Request $req){

    $curdate = $req->tdate;
    $lastweek = $req->fdate;
    $cgrp = CompGroup::find($req->gid);
    if($cgrp){

    } else {
      return redirect()->back()->withInput()->withErrors(['gid' => 'Selected group no longer exist']);
    }

    // date validation?
    $cdate = new Carbon($curdate);
    $ldate = new Carbon($lastweek);

    if($ldate->gt($cdate)){
      return redirect()->back()->withInput()->withErrors(['fdate' => 'From date is after to date']);
    }


    $lbl = [];
    $tier0 = [];
    $tierA = [];
    $tierB = [];
    $tierC = [];
    $tierD = [];
    $normaldistgraphs = [];
    $sumtable = [];

    foreach ($cgrp->Members as $onemember) {
      $c0 = 0;
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

      $psAverage = $perstaff->average('act_hrs');
      $psCount = $perstaff->count();
      $psSumVar = 0;


      foreach ($perstaff as $maybeonestaff) {

        if($maybeonestaff->exp_hrs == 0){
          if($maybeonestaff->act_hrs == 0){
            $cc++;
          } else {
            $cd++;
          }

        } else {
          $pers = $maybeonestaff->act_hrs / $maybeonestaff->exp_hrs * 100;
          if($pers == 0){
            $c0++;
          } elseif($pers < 50){
            $ca++;
          } elseif ($pers < 70) {
            $cb++;
          } elseif ($pers <= 100) {
            $cc++;
          } else {
            $cd++;
          }
        }

        // $psSumVar += pow(($maybeonestaff->act_hrs), 2);

      }

      // if($psCount > 3){
      //   $psStdDev = sqrt($psSumVar / $psCount);
      //
      //   // calculate the normal distribution for each staff
      //   foreach ($perstaff as $maybeonestaff) {
      //
      //   }
      //
      //
      // }



      array_push($tier0, $c0);
      array_push($tierA, $ca);
      array_push($tierB, $cb);
      array_push($tierC, $cc);
      array_push($tierD, $cd);

      $sumtable[] = [
        'div_id' => $onemember->id,
        'div_name' => $onemember->pporgunitdesc,
        't_0' => $c0,
        't_A' => $ca,
        't_B' => $cb,
        't_C' => $cc,
        't_D' => $cd,
        'total' => ($c0 + $ca + $cb + $cc + $cd)
      ];

    }

    // dd($lbl);

    $datasets = array([
          'label' => '0%',
          'data' => $tier0,
          'backgroundColor' => "rgba(88, 88, 88, 0.5)",
          'borderColor' => "rgba(100, 100, 100, 0.7)",
        ],[
          'label' => '0% - 50%',
          'data' => $tierA,
          'backgroundColor' => "rgba(255, 255, 0, 0.5)",
          'borderColor' => "rgba(255, 199, 0, 0.7)",
        ],
        [
          'label' => '50% - 70%',
          'data' => $tierB,
          'backgroundColor' => "rgba(51, 51, 204, 0.5)",
          'borderColor' => "rgba(51, 51, 204, 0.7)",
        ],
        [
          'label' => '70% - 100%',
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
      'sumchart' => $this->getStackBarChart($lbl, $datasets, $cgrp->name . ' performance between ' . $lastweek . ' and ' . $curdate),
      'sumtable' => $sumtable
    ]);

  }

  private function getNormDist($x, $mean, $stddev){
    $exp = pow($x - $mean, 2) / (2 * $stddev * $stddev) * -1;
    $bot = $stddev * sqrt(2 * M_PI);
    return 1 / $bot * pow(M_PI, $exp);
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
                 'LabelString' => 'Division',
               ]
             ]],
             'yAxes' => [[
               'display' => true,
               'stacked' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Staff Count',
               ]
             ]]
           ]
         ]);

    return $schart;
  }

  private function getScatterGraph($id, $title, $datasets ){
    $schart = app()->chartjs
         ->name('scgraph' . $id)
         ->type('scatter')
         ->size(['width' => 400, 'height' => 250])
         // ->labels($label)
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
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Time',
               ]
             ]],
             'yAxes' => [[
               'display' => true,
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
