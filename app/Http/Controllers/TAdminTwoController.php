<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\McoTravelReq;
use App\SapLeaveInfo;
use \Carbon\Carbon;

class TAdminTwoController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function leaveFlag(Request $req){
    $leave = SapLeaveInfo::find($req->lid);
    if($leave){
      $leave->load_status = 'N';
      $leave->save();
    }

    return redirect()->back();
  }

  public function McoReport(Request $req){

    $sdate = new Carbon;
    $sdate->subMonth();
    $edate = new Carbon;

    if($req->filled('fromdate')){
      $sdate = new Carbon($req->fromdate);
    }

    if($req->filled('todate')){
      $edate = new Carbon($req->todate);
    }

    $unlist = McoTravelReq::whereDate('request_date', '>=', $sdate)
      ->whereDate('request_date', '<=', $edate)->get();


    return view('mco.reports', [
      'lust' => $unlist,
      'mindate' => $sdate->toDateString(),
      'maxdate' => $edate->toDateString()
    ]);
  }


}
