<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Unit;
use App\place;
use App\building;
use App\reservation;
use App\ActivityType;
use App\Activity;
use App\CommonConfig;
use App\EventAttendance;
use App\ResourceRequest;
use App\DailyPerformance;
use \DateTime;
use \DateTimeZone;
use \DateInterval;
use App\common\HDReportHandler;
use App\common\UserRegisterHandler;
use App\common\GDWActions;
use App\common\NotifyHelper;
use App\Api\V1\Controllers\BookingHelper;

class UserController extends Controller
{
  private $bh;

    function __construct(){
      $this->bh = new BookingHelper;
    }

    // validate token
    public function validateToken(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_no' => ['required'],
        'pushnoti_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $luser = $req->user();

      if(strcasecmp($luser->staff_no, $req->staff_no) == 0){
        $luser->pushnoti_id = $req->pushnoti_id;
        $luser->save();

        return $this->respond_json(200, 'Success', []);
      }

      return $this->respond_json(403, 'Missmatched token', []);

    }

    // view info
    public function getCustInfo(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $staffdata = User::where('id', $req->staff_id)->first();

      // return $staffdata;
      if($staffdata){

      } else {
        return $this->respond_json(404, 'cust not found', []);
      }

      $staff = $this->getStaffInfo($staffdata);

      return $this->respond_json(200, 'OK', $staff);
    }

    public function ListAllowedBuilding(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $staff = User::where('id', $req->staff_id)->first();
      $ret = array();

      if(isset($staff->allowed_building)){
        $allowedbuilding = json_decode($staff->allowed_building);
        foreach ($allowedbuilding as $buildid) {
          array_push($ret, $this->bh->getBuildingStat($buildid));
        }
      }
      $errcode = 200;
      if($ret){
        $errcode = 300;
      }

      return $this->respond_json($errcode, 'Allowed buildings', $ret);

    }

    public function ReserveSeatV2(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'seat_id' => ['required'],
        'start_time' => ['required'],
        'end_time' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      // find this staff
      $thisstaff = User::where('id', $req->staff_id)->first();
      if($thisstaff){
        // release old reservation if any
        if(isset($thisstaff->curr_reserve)){
          return $this->respond_json(401, 'Already have previous reservation', $this->bh->getReserveInfo($thisstaff->curr_reserve));
        }

      } else {
        return $this->respond_json(404, 'staff 404', $input);
      }

      if($this->bh->checkOverlapReservation($req->seat_id, $req->start_time, $req->end_time)){
        return $this->respond_json(401, 'Overlap with another reservation', []);
      }

      // create the reservation record
      $reserve = new reservation;
      $reserve->place_id = $req->seat_id;
      $reserve->user_id = $req->staff_id;
      $reserve->expire_time = $req->end_time;
      $reserve->start_time = $req->start_time;
      $reserve->end_time = $req->end_time;
      $reserve->status = 1;
      $reserve->save();

      // update back to the user
      $thisstaff->curr_reserve = $reserve->id;
      $thisstaff->save();

      // return $this->respond_json(200, 'seat reserved', $reserve);
      return $this->respond_json(200, 'seat reserved', $this->bh->getReserveInfo($reserve->id));

    }

    // reserve seat
    public function ReserveSeat(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'seat_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      // find this staff
      $thisstaff = User::where('id', $req->staff_id)->first();
      if($thisstaff){
        // release old reservation if any
        $this->bh->clearReservation($thisstaff);
      } else {
        return $this->respond_json(404, 'staff 404', $input);
      }

      $checkseat = $this->bh->checkSeat($req->seat_id, 'id', $req->staff_id);

      if($checkseat['code'] != 200){
        return $checkseat;
      }

      $time = new DateTime('NOW');
      $time->setTimezone(new DateTimeZone('+0800'));
      $minutes_to_add = env('TRUST_RESERVE_DUR', 15);
      $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

      // proceed to reserve the seat
      $seat = place::where('id', $req->seat_id)->first();
      $seat->status = 2;
      $seat->reserve_staff_id = $req->staff_id;
      $seat->reserve_expire = $time;
      $seat->save();

      // create the reservation record
      $reserve = new reservation;
      $reserve->place_id = $req->seat_id;
      $reserve->user_id = $req->staff_id;
      $reserve->expire_time = $time;
      $reserve->status = 1;
      $reserve->save();

      // update back to the user
      $thisstaff->curr_reserve = $reserve->id;
      $thisstaff->save();

      // return $this->respond_json(200, 'seat reserved', $reserve);
      return $this->respond_json(200, 'seat reserved', $this->bh->getReserveInfo($reserve->id));

    }

    // cancel reservation
    function ReserveCancel(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $staff = User::where('id', $req->staff_id)->first();
      $this->bh->clearReservation($staff);

      return $this->respond_json(200, 'reservation cleared', []);

    }

    // check in from reservation
    function CheckinFromReserve(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'qr_code' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $theuser = User::where('id', $req->staff_id)->first();
      if(isset($theuser->curr_reserve)){
        // check reservation status
        $theresv = reservation::where('id', $theuser->curr_reserve)->first();
        if($theresv->status == 0){
          return $this->respond_json(401, 'Reservation no longer active and maybe overwritten by others', $theresv);
        }

        // check the seat this qr belongs to
        $seatbyqr = place::where('qr_code', $req->qr_code)->first();
        if($seatbyqr){
          // check if it's the same seat reserved earlier
          if($seatbyqr->id != $theresv->place_id){
            return $this->respond_json(401, 'Not the reserved seat');
          }
        } else {
          return $this->respond_json(404, 'QR code not registered');
        }

        // proceed with checkin
        $theresv->status = 0;
        $theresv->save();
        $cekin = $this->bh->checkIn($theuser, $theresv->place_id);
        return $this->respond_json(200, 'Checkin successful', $cekin);
      } else {
        // no active reservation
        return $this->respond_json(401, 'No active reservation', $this->getStaffInfo($theuser));
      }
    }

    // direct check in
    function CheckinDirect(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'seat_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      // just in case, check the status
      $seatstatys = $this->bh->checkSeat($req->seat_id, 'id', $req->staff_id, true);

      if($seatstatys['code'] != 200 && $seatstatys['code'] != 201){
        return $seatstatys;
      }

      $lat = 0;
      $long = 0;

      if($req->filled('lat')){
        $lat = $req->lat;
      }

      if($req->filled('long')){
        $long = $req->long;
      }

      // // UNCOMMENT THIS TO RE-ENABLE GEOLOCATION VALIDATION
      $cconfig = CommonConfig::where('key', 'geo_checkin')->first();
      if($cconfig && $cconfig->value == 'true'){
        if($this->bh->inCorrectPlace($req->seat_id, $lat, $long) == false){
          return $this->respond_json(403, 'Not in correct location', $input);
        }
      }


      $theuser = User::where('id', $req->staff_id)->first();
      $evid = 0;
      $evat = 0;

      if($req->filled('event_att_id')){
        $thatevent = EventAttendance::find($req->event_att_id);
        if($thatevent){
          $evat = $thatevent->id;
          $evid = $thatevent->AreaEvent->id;
        } else {
          return $this->respond_json(404, 'Invalid event att id', $input);
        }
      }

      $cekin = $this->bh->checkIn($theuser, $req->seat_id, $lat, $long, $evid, $evat);
      return $this->respond_json(200, 'Checkin successful', $cekin);
    }

    // check out
    function CheckOut(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

      $validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $this->bh->checkOut($req->staff_id);

      return $this->respond_json(200, 'Checkout successful');

    }

    function Find(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'input' => ['required']
  		];

      $validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $finder = new HDReportHandler;
      $data = $finder->findStaff($req->input);
      $errc = 200;
      if($data->count() == 0){
        $errc = 404;
      }

      foreach ($data as $key => $value) {
        $value->Avatar;
      }

      return $this->respond_json($errc, 'Search result for ' . $req->input, $data);

    }

    public function getGwdRank(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];


      // update the rank
      $av = [
        'rank' => GDWActions::updateAvatar($req->staff_id)
      ];


      // get which div this user belongs to
      $cuser = User::find($req->staff_id);
      if($cuser){
        // get best staff in that div
        $topdp = DailyPerformance::where('unit_id', $cuser->lob)
          ->whereDate('record_date', date('Y-m-d'))
          ->orderBy('actual_hours', 'DESC')
          ->first();

        if($topdp){
          $av['top_in_div'] = [
            'staff' => $topdp->User->name,
            'hours' => $topdp->actual_hours
          ];
        }

        $av['grp_stats_percentage'] = GDWActions::getGroupSummary($cuser->unit_id);

      }


      return $this->respond_json(200, 'Currrent avatar', $av);
    }


    public function clockIn(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'in_time' => ['required']
  		];

      $validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $user = UserRegisterHandler::attClockIn($req);

      return $this->respond_json(200, 'clocked-in', $this->getStaffInfo($user));

    }

    public function clockOut(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'out_time' => ['required']
  		];

      $validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      if($req->filled('reason')){
        $reason = $req->reason;
      } else {
        $reason = 'clock-out';
      }

      $lat = 0;
      $long = 0;

      if($req->filled('lat')){
        $lat = $req->lat;
      }

      if($req->filled('long')){
        $long = $req->long;
      }

      $this->bh->checkOut($req->staff_id);
      $attendance = UserRegisterHandler::attClockOut($req->staff_id, $req->out_time, $lat, $long, $reason);


      return $this->respond_json(200, 'clocked-out', $attendance);

    }

    public function requestSeatAccess(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required'],
        'seat_id' => ['required']
  		];

      $validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $theseat = place::find($req->seat_id);
      $time = date('Y-m-d H:i:s');

      if($theseat){
        //check current occupant of that seat
        if(isset($theseat->checkin_staff_id)){
          $req_to_id = User::find($theseat->checkin_staff_id);
          $reqstatus = 'Checkin';
          $body = 'Another staff is requesting you to release the seat that currently occupied by you';
        } else {
          // not checked in yet. check for booking
          $curbook = reservation::where('status', 1)
            ->where('place_id', $theseat->id)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)->first();

          if($curbook){
            // is a reservation
            $req_to_id = User::find($curbook->user_id);
            $reqstatus = 'reservation';
            $body = 'Another staff is requesting you to release the seat that is currently booked by you';
          } else {
            // no booking either. meaning the seat is actually free
            return $this->respond_json(401, 'seat actually free', $theseat);
          }

        }

        $req_by = User::find($req->staff_id);
        // create the resource request
        $res_req = new ResourceRequest;
        $res_req->request_by = $req_by->id;
        $res_req->request_to = $req_to_id->id;
        $res_req->resource_model = 'place';
        $res_req->resource_id = $theseat->id;
        $res_req->request_time = $time;
        $res_req->status = $reqstatus;
        $res_req->save();

        // send the notification
        $resp = NotifyHelper::SendPushNoti(
          $req_to_id->pushnoti_id,
          'Seat Request: ' . $theseat->label,
          $body,
          [
            'req_id' => $res_req->id,
            'action' => 'seat request',
            'msg' => "$req_by->name requested to sit at $theseat->label"]
        );

        return $this->respond_json(200, 'Alert sent', $resp);

      } else {
        return $this->respond_json(404, 'seat not found', $input);
      }
    }

    public function denySeatRequest(Request $req){
      $input = app('request')->all();
  		$rules = [
  			'req_id' => ['required']
  		];

      $validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $resreq = ResourceRequest::find($req->req_id);
      $time = date('Y-m-d H:i:s');

      if($resreq){
        $theseat = place::find($resreq->resource_id);
        $reqer = User::find($resreq->request_by);
        $resreq->response_time = $time;
        $resreq->status = 'denied';
        $resreq->save();

        // respond
        $resp = NotifyHelper::SendPushNoti(
          $reqer->pushnoti_id,
          'Seat Request Denied: ' . $theseat->label,
          'Your request for this seat has been denied by current occupant'
        );

        return $this->respond_json(200, 'request denied', $resp);


      } else {
        return $this->respond_json(404, 'Request ID not found', $input);
      }

    }

    public function acceptSeatRequest(Request $req){
      $input = app('request')->all();
  		$rules = [
  			'req_id' => ['required']
  		];

      $validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $resreq = ResourceRequest::find($req->req_id);
      $time = date('Y-m-d H:i:s');
      if($resreq){
        $theseat = place::find($resreq->resource_id);
        $curstat = $resreq->status;

        $reqer = User::find($resreq->request_by);
        $reqto = User::find($resreq->request_to);
        $resreq->response_time = $time;
        $resreq->status = 'seat released';
        $resreq->save();

        $tbh = new BookingHelper;
        if($curstat == 'reservation'){
          // release the reservation
          $tbh->clearReservation($reqto);
        } else {
          // check out
          $tbh->checkOut($resreq, 'Release seat to others');
        }

        // notify the requestor about the seat availability
        $resp = NotifyHelper::SendPushNoti(
          $reqer->pushnoti_id,
          'Seat is now available: ' . $theseat->label,
          'The previous occupant has released this seat'
        );

        return $this->respond_json(200, 'Seat released', $resp);

      } else {
        return $this->respond_json(404, 'Request ID not found', $input);
      }

    }


    // internal functions ======================

    public function updateStaffProfile(Request $req){

    }

    private function getStaffInfo($staffdata){

      $staff = [
        'status' => $staffdata->status,
        'name' => $staffdata->name,
        'email' => $staffdata->email,
        'mobile_no' => $staffdata->mobile_no,
        'photo_url' => $staffdata->photo_url,
        'staff_id' => $staffdata->staff_id,
        'role' => $staffdata->role,
        'pushnoti_id' => $staffdata->pushnoti_id,
        'avatar' => $staffdata->Avatar,
        'allowed_building' => json_decode($staffdata->allowed_building)
      ];

      if(isset($staffdata->curr_reserve)){
        $staff['curr_reserve'] = $this->bh->getReserveInfo($staffdata->curr_reserve);
      }

      if(isset($staffdata->curr_checkin)){
        $staff['curr_checkin'] = $this->bh->getCheckinInfo($staffdata->curr_checkin);
      }

      if(isset($staffdata->last_checkin)){
        $staff['last_checkin'] = $this->bh->getCheckinInfo($staffdata->last_checkin);
      }

      if(isset($staffdata->curr_attendance)){
        $staff['attendance'] = $staffdata->Attendance;
      }

      return $staff;
    }

    public function getActivityList($task_id){
      $actlist = Activity::where('task_id', $task_id)
        ->orderBy('date', 'ASC')
        ->get();

      // append the readable activity type
      foreach ($actlist as $aact) {
        $acttype = ActivityType::find($aact->act_type);
        $aact['act_type_desc'] = $acttype->descr;
      }

      return $actlist;
    }

    public function pg(Request $req){
      return $req->user();
    }

    public function testNotify(Request $req){
      if($req->filled('s')){
        $user = User::where('staff_no', $req->s)
          ->whereNotNull('pushnoti_id')->first();

        if($user){

          $data = [
            'req_id' => 1
          ];

          return NotifyHelper::SendPushNoti($user->pushnoti_id, 'Tajuk', 'Isi dalam', $data);
        }

      }

      return 'hi';
    }
}
