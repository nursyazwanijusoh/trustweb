<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\common\McoActions;
use App\McoTravelReq;
use App\LocationHistory;
use App\User;
use PDF;

class McoController extends Controller
{
    function GetGmInfo(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $staff = User::find($req->staff_id);
      if($staff){
        $gm = McoActions::FindAtLeastGm($staff);
      } else {
        return $this->respond_json(404, 'Staff not found', []);
      }

      return $this->respond_json(200, 'At least GM', $gm);

    }

    function requestMcoAck(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'reqdate' => ['required'],
        'location' => ['required'],
        'reason' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $resp = McoActions::SubmitApplication($req->staff_id, $req->location, $req->reqdate, $req->reason);

      if($resp == 200){
        return $this->respond_json(200, 'Success', []);
      } else {
        return $this->respond_json(500, $resp, []);
      }


    }

    function requestList(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $list = McoTravelReq::where('requestor_id', $req->staff_id)
        ->latest()->limit(10)->get();

      return $this->respond_json(200, 'Success', $list);
    }

    function getPermit(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'mid' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $mco = McoTravelReq::find($req->mid);
      if($mco){
        $pdf = PDF::loadView('mco.permit', [
          'date' => $mco->request_date,
          'name' => $mco->requestor->name,
          'newic' => $mco->requestor->new_ic,
          'seq' => $mco->id
        ]);

        return $pdf->stream('permit.pdf');
      } else {
        return $this->respond_json(404, 'Not found', []);
      }
    }

    function takeaction(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'mid' => ['required'],
        'action' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      if($req->action == 'approve'){
        $resp = McoActions::ApproveApplication($req->mid, $req->user()->id);
      } elseif($req->action == 'reject'){
        $resp = McoActions::RejectApplication($req->mid, $req->user()->id);
      } else {
        return $this->respond_json(500, 'unknown action', []);
      }

      return $this->respond_json(200, 'Success', []);
    }

    function getpending(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $pending = McoTravelReq::where('approver_id', $req->staff_id)
      ->where('status', 'Pending Approval')->get();
      foreach ($pending as $key => $value) {
        $value->requestor_name = $value->requestor->name;
      }


      return $this->respond_json(200, 'Success', $pending);
    }

    function getapproved(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $approved = McoTravelReq::where('approver_id', $req->staff_id)
      ->where('status', 'Approved')->get();


      return $this->respond_json(200, 'Success', $approved);
    }

    function getmcocheckin(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'mid' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $mco = McoTravelReq::find($req->mid);
      if($mco){
        $checkins = LocationHistory::where('user_id', $mco->requestor_id)
          ->whereDate('created_at', $mco->request_date)
          ->latest()
          ->get();

        return $this->respond_json(200, 'Success', $checkins);

      } else {
        return $this->respond_json(404, 'Not found', []);
      }
    }


}
