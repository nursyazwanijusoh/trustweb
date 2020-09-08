<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\reservation;
use App\Checkin;
use App\building;
use App\Office;
use App\place;
use App\User;
use App\LocationHistory;
use \DateTime;
use \DateTimeZone;
use App\common\NotifyHelper;

/**
 * class to manage all booking related activities
 */
class BookingHelper extends Controller
{
  public function getReserveInfo($reserve_id){
    $resv = reservation::where('id', $reserve_id)->first();

    // get the place info
    $resv->loc_detail = $this->getPlaceInfo($resv->place_id);

    return $resv;
  }

  public function getCheckinInfo($check_id){
    $cek = Checkin::where('id', $check_id)->first();
    $cek->loc_detail = $this->getPlaceInfo($cek->place_id);

    return $cek;
  }

  public function getPlaceInfo($place_id){
    $place = place::where('id', $place_id)->first();
    $thebuild = $this->getBuildingInfo($place->building_id);
    $place->floor_name = $thebuild->floor_name;
    $place->building_name = $thebuild->building_name;
    $place->unit = $thebuild->unit;
    $place->loc_name = $thebuild->floor_name . '@' . $thebuild->building_name;

    return $place;
  }

  public function getBuildingInfo($building_id){
    $build = building::findOrFail($building_id);

    return $build; //->floor_name . '@' . $build->building_name;

  }

  public function getCheckinMinimal($check_id){
    $cek = $this->getCheckinInfo($check_id);
    return $cek->loc_detail->loc_name . ' -> ' . $cek->loc_detail->label . ' since ' . $cek->checkin_time;
  }

  public function checkOut($staff_id, $remark = 'manual'){
    $time = new DateTime('NOW');
    $time->setTimezone(new DateTimeZone('+0800'));

    $staff = User::where('id', $staff_id)->first();

    if(isset($staff->curr_checkin)){
      // get current check in info and update it
      $cekin = Checkin::where('id', $staff->curr_checkin)->first();
      $cekin->checkout_time = $time;
      $cekin->remark = $remark;
      $cekin->save();

      // clear the place
      $place = place::where('id', $cekin->place_id)->first();
      $place->checkin_staff_id = null;
      $place->status = 1;
      $place->save();

      // clear the checkin
      $staff->curr_checkin = null;
      $staff->save();

      if($remark != 'manual'){
        $resp = NotifyHelper::SendPushNoti(
          $staff->pushnoti_id,
          'Checked out from seat ' . $place->label,
          'Check out reason: ' . $remark);
      }

    }

  }

  public function checkIn($staff, $seat_id, $lat = 0, $long = 0, $event_id = 0, $ev_att_id = 0){
    $time = new DateTime('NOW');
    $time->setTimezone(new DateTimeZone('+0800'));

    // clear the reservation
    // $this->clearReservation($staff);
    // check out from current, if exist
    $this->checkOut($staff->id, 'checking in elsewhere');

    // set the seat status
    $place = place::find($seat_id);

    // only change the status if it's normal seat
    if($place->seat_type == 1){
      $place->status = 3;
      $place->checkin_staff_id = $staff->id;
      $place->save();
    }

    // create checkin
    $cekin = new Checkin;
    $cekin->checkin_time = $time;
    $cekin->place_id = $seat_id;
    $cekin->user_id = $staff->id;
    $cekin->latitude = $lat;
    $cekin->longitude = $long;

    // link the events
    if($ev_att_id != 0){
      $cekin->area_event_id = $event_id;
      $cekin->event_attendance_id = $ev_att_id;
    }

    $cekin->save();

    $okaceinfo = $this->getPlaceInfo($seat_id);

    $lochist = new LocationHistory;
    $lochist->user_id = $staff->id;
    $lochist->latitude = $lat;
    $lochist->longitude = $long;
    $lochist->action = 'Agile Seat';
    $lochist->address = $okaceinfo->loc_name;
    $lochist->save();

    // update back to the staff
    $staff->curr_checkin = $cekin->id;
    $staff->last_checkin = $cekin->id;
    $staff->last_location_id = $lochist->id;
    $staff->save();

    return $cekin;

  }

  // this function only consider scenarios in malaysia (North-east quadrant)
  public function inCorrectPlace($seat_id, $lat, $long){
    $place = place::find($seat_id);

    if($lat == 0){
      // for cases without coordinate, just accept it
      return true;
    }

    $off = $place->building->office;
    // check if this coordinate is within this office square
    if($lat >= $off->a_latitude && $lat <= $off->b_latitude){
      if($long <= $off->a_longitude && $lat >= $off->b_longitude){
        return true;
      }
    }

    return false;
  }

  public function kickAllOut(){
    $allstaff = User::whereNotNull('curr_checkin')->get();
    $counter = 0;

    foreach ($allstaff as $onestaff) {
      $this->checkOut($onestaff->id, 'end of day');
      $counter++;
    }

    // just in case, search for occupied seats

    $allseats = place::where('status', '>', '2')->get();
    foreach ($allseats as $oneseat) {
      $oneseat->status = 1;
      // $oneseat->reserve_staff_id = null;
      // $oneseat->reserve_expire = null;
      $oneseat->checkin_staff_id = null;
      $oneseat->save();
      $counter++;
    }

    return $counter;
  }

  public function removeExpiredReservation(){
    $time = new DateTime('NOW');
    $time->setTimezone(new DateTimeZone('+0800'));

    $allreserve = reservation::where('status', 1)
      ->where('expire_time', '<', $time)->get();
    $counter = 0;

    foreach($allreserve as $areserve){
      // get the reservation info
      $astaff = User::where('id', $areserve->staff_id)->first();
      if(isset($astaff->curr_reserve)){
        if($areserve->id == $astaff->curr_reserve){
          $this->clearReservation($astaff);
        } else {
          $areserve->status = 0;
          $areserve->save();
        }
      } else {
        $areserve->status = 0;
        $areserve->save();
      }

      $counter++;
    }
    return $counter;
  }

  public function getBuildingStat($building_id, $canbook = 0){

    if($canbook == 0){
      $totalcount = place::where('building_id', $building_id)->where('status', '!=', 0)->where('seat_type', 1)->count();
      $freecount = place::where('building_id', $building_id)->where('status', 1)->where('seat_type', 1)->count();
      $reservedcount = place::where('building_id', $building_id)->where('status', 2)->where('seat_type', 1)->count();
      $occupiedcount = place::where('building_id', $building_id)->where('status', 3)->where('seat_type', 1)->count();
    } else {
      $totalcount = place::where('building_id', $building_id)
        ->where('status', '!=', 0)
        ->where('bookable', true)
        ->where('seat_type', 1)
        ->count();

      $freecount = place::where('building_id', $building_id)
        ->where('status', 1)
        ->where('bookable', true)
        ->where('seat_type', 1)->count();

      $reservedcount = place::where('building_id', $building_id)
        ->where('status', 2)
        ->where('bookable', true)
        ->where('seat_type', 1)->count();

      $occupiedcount = place::where('building_id', $building_id)
        ->where('status', 3)
        ->where('bookable', true)
        ->where('seat_type', 1)->count();
    }

    $persen = $totalcount > 0 ? ($totalcount - $freecount) / $totalcount * 100 : 0;

    $thebuild = $this->getBuildingInfo($building_id);
    $loc_name = $thebuild->floor_name . ' @ ' . $thebuild->building_name;

    return [
      'building_id' => $building_id,
      'building_name' => $loc_name,
      'unit' => $thebuild->unit,
      'total_seat' => $totalcount,
      'free_seat' => $freecount,
      'reserved_seat' => $reservedcount,
      'occupied_seat' => $occupiedcount,
      'usage_percent' => round($persen)
    ];
  }

  public function checkSeat($seat_id, $key = 'id', $req_id = 0, $forcheckin = false){
    // check if the seat is available
    $theseat = place::where($key, $seat_id)->first();
    if($theseat){
      if($theseat->status == 0){
        return $this->respond_json(401, 'seat disabled', $theseat);
      } elseif ($theseat->status == 3) {
        $theseat->Occupant;
        return $this->respond_json(401, 'seat occupied', $theseat);
      } elseif ($theseat->status == 2 && $theseat->reserve_staff_id != $req_id) {
        return $this->respond_json(402, 'seat reserved', $theseat);
      } else {

        $time = date('Y-m-d H:i:s');
        // seat is free. check for upcoming booking
        $todayd = date('Y-m-d');
        $bookinglist = reservation::where('status', 1)
          ->where('place_id', $theseat->id)
          ->whereDate('start_time', $todayd)
          ->where('end_time', '>', $time)->get();

        foreach($bookinglist as $buk){
          if($buk->start_time < $time && $buk->end_time > $time){
            // currently being booked but not update the status yet
            // $theseat->status = 2;
            $theseat->reserve_staff_id = $buk->user_id;
            $theseat->save();

            if($forcheckin){
              if($buk->user_id == $req_id){
                $buk->status = 0;
                $buk->save();
                $theseat->reserve_staff_id = null;
                $theseat->save();
                return $this->respond_json(200, 'Seat available', $theseat);
              }
            }

            return $this->respond_json(402, 'seat reserved', $theseat);
          }
        }

        if(sizeof($bookinglist) != 0){
          $theseat->upcoming_booking = $bookinglist;
          return $this->respond_json(200, 'upcoming booking', $theseat);
        }
      }

    } else {
      return $this->respond_json(404, 'seat not found', ['value' => $seat_id, 'key' => $key]);
    }

    // check building status
    $thebuildi = building::where('id', $theseat->building_id)->first();
    if($thebuildi->status == 0){
      return $this->respond_json(401, 'building disabled', $thebuildi);
    }

    // check for events
    if($theseat->seat_type == 2){
      $events = [];
      // get current and upcoming event
      $evs = $theseat->NearEvent;
      if($evs->count() != 0){
        foreach($evs as $onev){
          $oet = $onev->EvenAttToday;
          if($oet){
            array_push($events, $oet);
          }
        }
        unset($theseat['NearEvent']);
        $theseat->event_att = $events;
        return $this->respond_json(200, 'Have Event', $theseat);
      }

      unset($theseat['NearEvent']);
    }

    // OK. proceed
    return $this->respond_json(200, 'Seat available', $theseat);
  }

  public function clearReservation($user){
    if(isset($user->curr_reserve)){
      // find the place id of this reservation
      $resv = reservation::where('id', $user->curr_reserve)->first();
      if($resv){
        $resv->status = 0;
        $resv->save();
        $tempat = place::where('id', $resv->place_id)->first();
        if($tempat){
          // only remove reservation if it's the same user
          if($tempat->reserve_staff_id == $user->id){
            $tempat->reserve_staff_id = null;
            $tempat->reserve_expire = null;
            $tempat->status = 1;
            $tempat->save();
          }
        }
      }
      $user->curr_reserve = null;
      $user->save();
    }
  }

  public function getCheckinHistory($staffid){
    $lastmon = date("Y-m-d", strtotime("-1 months"));
    $cekins = Checkin::where('user_id', $staffid)
      ->whereDate('checkin_time', '>', $lastmon)
      ->get();

    return $cekins;
  }

  public function checkOverlapReservation($seat_id, $stime, $etime){

    $olapres = reservation::where('status', 1)
      ->where('place_id', $seat_id)
      ->where('start_time', '<', $etime)
      ->where('end_time', '>', $stime)
      ->count();

    // $temp = [
    //   'seat' => $seat_id,
    //   'stime' => $stime,
    //   'etime' => $etime,
    //   'count' => $olapres
    // ];
    //
    // dd($temp);

    return $olapres != 0;
  }


}
