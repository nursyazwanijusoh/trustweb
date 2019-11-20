<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\building;
use App\reservation;
use App\Office;
use App\place;
use \Carbon\Carbon;

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
      $build->created_by = $req->created_by;
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
        // delete the seats first
        place::where('building_id', $build->id)->delete();

        // then delete the building
        $build->delete();
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
          $build->delete();
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

        $tvuild = building::find($req->building_id);
        if(!$tvuild){
          return $this->respond_json(404, 'not found', $req->all());
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

      function buildingListSeats(Request $req){
        $input = app('request')->all();

        $rules = [
          'building_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $seats = place::where('building_id', $req->building_id)->where('seat_type', 1)->get();

        $tom = date("Y-m-d",strtotime("tomorrow"));
        foreach($seats as $ast){
          // check for tomorrow's reservation
          $bukaun = reservation::whereDate('start_time', $tom)
            ->where('status', 1)
            ->where('place_id', $ast->id)
            ->count();

          $ast->book_count = $bukaun;
        }

        return $this->respond_json(200, 'seat list', $seats);

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

      // get office building list, that have the requested item
      function getOfficeBuilding(Request $req){
        $input = app('request')->all();

        $rules = [
          'type' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $stype = $req->type == 'seat' ? 1 : 2;
        $buk = $req->filled('book') ? 1 : 0;

        $oflist = Office::all();
        $retdata = [];
        foreach ($oflist as $key => $value) {
          // dd($value->hasAsset($stype));
          $asset = $value->buildingWithAsset($stype, $buk);
          if(empty($asset)) {
            // unset($oflist[$key]);
          } else {
            // $value->floorcount = $asset->count();
            unset($value['building']);
            array_push($retdata, $value);
          }
        }

        return $this->respond_json(200, 'Office building with asset ' . $req->type, $retdata);

      }

      function getOfficeFloor(Request $req){
        $input = app('request')->all();

        $rules = [
          'office_id' => ['required'],
          'type' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $bookh = new BookingHelper;
        $stype = $req->type == 'seat' ? 1 : 2;
        $buk = $req->filled('book') ? 1 : 0;

        $ofc = Office::find($req->office_id);
        if($ofc){

          if($stype == 1){
            $ret = [];
            $buildlist = $ofc->buildingWithAsset($stype, $buk);
            foreach ($buildlist as $abuild) {
              array_push($ret, $bookh->getBuildingStat($abuild->id, $buk));
            }

            return $this->respond_json(200, 'Floor with asset ' . $req->type, $ret);
          } else {
            return $this->respond_json(200, 'Floor with asset ' . $req->type, $ofc->buildingWithAsset($stype));
          }

        } else {
          return $this->respond_json(404, 'Office not found', $input);
        }
      }

      function getOfficeArea(Request $req){
        $input = app('request')->all();

        $rules = [
          'floor_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $floor = building::find($req->floor_id);
        if($floor){

          $rooms = $floor->MeetingRooms;

          foreach($rooms as $aroom){
            $cks = $aroom->Checkin;
            if($cks){
              $aroom->usercount = $cks->count();
            } else {
              $aroom->usercount = 0;
            }

            unset($aroom['Checkin']);
          }

          return $this->respond_json(200, 'Area for ' . $floor->floor_name, $rooms);
        } else {
          return $this->respond_json(404, 'Floor not found', $input);
        }

      }

      public function getTomorrowAvailability(Request $req){
        $input = app('request')->all();

        $rules = [
          'floor_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $tflor = building::find($req->floor_id);

        if($tflor){
          $tomo = Carbon::tomorrow();
          $seatlist = $tflor->bookable;

          foreach ($seatlist as $key => $value) {
            // check whether this seat is booked tomorrow
            $bcount = reservation::where('place_id', $value->id)
              ->where('status', 1)
              ->whereDate('start_time', $tomo->format('Y-m-d'))
              ->count();

            if($bcount == 0){
              $value->gotbooking = false;
            } else {
              $value->gotbooking = true;
            }
          }

          return $this->respond_json(200, 'tomorrow seat status', $seatlist);

        } else {
          return $this->respond_json(404, 'Floor not found', $input);
        }

      }

}
