<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
      ->select('unit', \DB::raw('count(1) as total'))
      ->where('lob', \Session::get('staffdata')['lob'])
      ->groupBy('unit')->get();

    $label = [];
    $value = [];
    foreach($unitlist as $aunit){
      array_push($label, $aunit->unit);
      array_push($value, $aunit->total);
    }

    $schart = new RegStatChart;
    $schart->labels($label);
    $schart->dataset('Registered user', 'horizontalBar', $value);

    return view('report.regstat', ['chart' => $schart]);
  }

  public function manDaysDisp(Request $req){

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
