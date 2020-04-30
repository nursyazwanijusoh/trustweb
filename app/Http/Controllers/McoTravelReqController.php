<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\common\McoActions;
use App\McoTravelReq;
use App\LocationHistory;
use PDF;

class McoTravelReqController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

  public function reqform(Request $req){
    // set min and max date
    $mindate = new Carbon;
    $maxdate = new Carbon;
    $maxdate->addDay();
    $gm = McoActions::FindAtLeastGm($req->user());

    $reqhist = McoTravelReq::where('requestor_id', $req->user()->id)->get();

    return view('mco.req_ack', [
      'mindate' => $mindate->toDateString(),
      'maxdate' => $maxdate->toDateString(),
      'gm' => $gm,
      'hist' => $reqhist
    ]);
  }

  public function submitform(Request $req){
    $resp = McoActions::SubmitApplication($req->user()->id, $req->location, $req->reqdate, $req->reason);

    if($resp == 200){
      return redirect(route('mco.reqform'))->with([
        'alert' => 'Application Submitted',
        'a_type' => 'info'
      ]);
    } else {
      return redirect()->back()->withInput()->with([
        'alert' => $resp,
        'a_type' => 'warning'
      ]);
    }

  }

  public function ackreqs(Request $req){

    $pending = McoTravelReq::where('approver_id', $req->user()->id)
      ->where('status', 'Pending Approval')->get();

    $approved = McoTravelReq::where('approver_id', $req->user()->id)
      ->where('status', 'Approved')->get();

    return view('mco.ackreqs', [
      'pending' => $pending,
      'approved' => $approved
    ]);
  }

  public function takeaction(Request $req){
    if($req->filled('action')){
      if($req->action == 'approve'){
        $resp = McoActions::ApproveApplication($req->mid, $req->user()->id);
      } elseif($req->action == 'reject'){
        $resp = McoActions::RejectApplication($req->mid, $req->user()->id);
      } else {
        return redirect(route('staff'));
      }

      return redirect(route('mco.ackreqs'));

    } else {
      return redirect(route('staff'));
    }
  }

  public function checkins(Request $req){

    if($req->filled('mid')){
      $mco = McoTravelReq::find($req->mid);
      if($mco){

        // dd($mco->request_date);

        $checkins = LocationHistory::where('user_id', $mco->requestor_id)
          ->whereDate('created_at', $mco->request_date)
          ->latest()
          ->get();

        return view('mco.mcocheckins', [
          'mco' => $mco,
          'cekins' => $checkins
        ]);

      } else {
        abort(404);
      }
    } else {
      return redirect(route('staff'));
    }


  }

  public function getpermit(Request $req){

    if($req->filled('mid')){
      $mco = McoTravelReq::find($req->mid);
      if($mco){
        $pdf = PDF::loadView('mco.permit', [
          'date' => $mco->request_date,
          'name' => $mco->requestor->name,
          'newic' => $mco->requestor->new_ic,
          'seq' => $mco->id
        ]);

        return $pdf->download('permit.pdf');
      } else {
        abort(404);
      }
    } else {
      return redirect(route('staff'));
    }





    // return view('mco.permit', [
    //   'date' => '2020-04-10',
    //   'name' => 'Ali bin Abu',
    //   'newic' => 'C51421',
    //   'seq' => 201
    // ]);
  }

}
