<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\building;
use App\place;

/**
 * Controller for buildings / tables
 */
class InfraController extends Controller
{
  // ============================================
  // = main building / floor / location handler =
  // ============================================

    function buildingCreate(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'building_name' => ['required'],
  			'floor_name' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $build = new building;
      $build->building_name = $req->building_name;
      $build->floor_name = $req->floor_name;
      $build->status = 1;

      if($req->filled('remark')){
        $build->remark = $req->remark;
      }

      $build->save();

      return $this->respond_json(200, 'data saved', $build);
    }

    function buildingEdit(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'building_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $build = building::where('id', $req->building_id)->first();

      if($build){
        if($req->filled('building_name')){
          $build->building_name = $req->building_name;
        }

        if($req->filled('floor_name')){
          $build->floor_name = $req->floor_name;
        }

        if($req->filled('remark')){
          $build->remark = $req->remark;
        }

        if($req->filled('status')){
          $build->status = $req->status;
        }

        $build->save();
        return $this->respond_json(200, 'data saved', $build);
      } else {
        return $this->respond_json(404, 'record not found', $input);
      }
    }

    function buildingDelete(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'building_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $build = building::where('id', $req->building_id)->first();

      if($build){
        return $this->respond_json(200, 'record deleted', []);
      } else {
        return $this->respond_json(404, 'record not found', $input);
      }

    }

    function buildingSearch(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'key' => ['required'],
        'value' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $builds = building::where($req->key, $req->value)->get();

      return $this->respond_json(200, 'result', $builds);

    }


    // ================
    // = seat handler =
    // ================

      function seatCreate(Request $req){
        $input = app('request')->all();

        $rules = [
          'building_id' => ['required'],
          'seat_type' => ['required'],
          'priviledge' => ['required'],
          'label' => ['required'],
          'qr_code' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $build = new place;
        $build->building_id = $req->building_id;
        $build->seat_type = $req->seat_type;
        $build->priviledge = $req->priviledge;
        $build->label = $req->label;
        $build->qr_code = $req->qr_code;
        $build->status = 1;

        $build->save();

        return $this->respond_json(200, 'data saved', $build);
      }

      function seatEdit(Request $req){
        $input = app('request')->all();

        $rules = [
          'seat_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $build = place::where('id', $req->seat_id)->first();

        if($build){
          if($req->filled('seat_type')){
            $build->seat_type = $req->seat_type;
          }

          if($req->filled('priviledge')){
            $build->priviledge = $req->priviledge;
          }

          if($req->filled('label')){
            $build->label = $req->label;
          }

          if($req->filled('qr_code')){
            $build->qr_code = $req->qr_code;
          }

          if($req->filled('status')){
            $build->status = $req->status;
          }

          $build->save();
          return $this->respond_json(200, 'data saved', $build);
        } else {
          return $this->respond_json(404, 'record not found', $input);
        }
      }

      function seatDelete(Request $req){
        $input = app('request')->all();

        $rules = [
          'seat_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $build = place::where('id', $req->seat_id)->first();

        if($build){
          return $this->respond_json(200, 'record deleted', []);
        } else {
          return $this->respond_json(404, 'record not found', $input);
        }

      }

      function seatSearch(Request $req){
        $input = app('request')->all();

        $rules = [
          'key' => ['required'],
          'value' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $builds = place::where($req->key, $req->value)->get();

        return $this->respond_json(200, 'result', $builds);

      }

      //-------- misc -----------
      function massKickOut(){
        $bookh = new BookingHelper;
        $kickcount = $bookh->kickAllOut();

        return $this->respond_json(200, 'Forced check out', ['count' => $kickcount]);
      }

      function reserveExpired(){
        $bookh = new BookingHelper;
        $kickcount = $bookh->removeExpiredReservation();
        return $this->respond_json(200, 'Expired reservation', ['count' => $kickcount]);
      }

      function buildingGetSummary(Request $req){
        $input = app('request')->all();

        $rules = [
          'building_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $bookh = new BookingHelper;
        $bs = $bookh->getBuildingStat($req->building_id);

        return $this->respond_json(200, 'Building summary', $bs);
      }

      function buildingAllSummary(){
        $bookh = new BookingHelper;
        $buildlist = building::all();
        $ret = [];
        foreach ($buildlist as $abuild) {
          array_push($ret, $bookh->getBuildingStat($abuild->id));
        }

        return $this->respond_json(200, 'ALL Building summary', $ret);

      }


      // get seat info from QR code
      function seatScanQR(Request $req){
        $input = app('request')->all();

        $rules = [
          'qr_code' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $bookh = new BookingHelper;
        return $bookh->checkSeat($req->qr_code, 'qr_code');
      }
}
