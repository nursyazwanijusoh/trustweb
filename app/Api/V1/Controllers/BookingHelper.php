<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\reservation;
use App\Checkin;
use App\building;
use App\Office;
use App\place;
use App\User;
use \DateTime;
use \DateTimeZone;

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
    $build = building::where('id', $building_id)->first();

    return $build; //->floor_name . '@' . $build->building_name;

  }

  public function getCheckinMinimal($check_id){
    $cek = $this->getCheckinInfo($check_id);
    return $cek->loc_detail->loc_name . ' -> ' . $cek->loc_detail->label . ' since ' . $cek->checkin_time;
  }

  public function checkOut($staff_id, $remark = ''){
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
    }

  }

  public function checkIn($staff, $seat_id, $lat = 0, $long = 0){
    $time = new DateTime('NOW');
    $time->setTimezone(new DateTimeZone('+0800'));

    // clear the reservation
    $this->clearReservation($staff);

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
    $cekin->save();

    // update back to the staff
    $staff->curr_checkin = $cekin->id;
    $staff->last_checkin = $cekin->id;
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

    $allseats = place::where('status', '>', '1')->get();
    foreach ($allseats as $oneseat) {
      $oneseat->status = 1;
      $oneseat->reserve_staff_id = null;
      $oneseat->reserve_expire = null;
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

  public function getBuildingStat($building_id){
    $totalcount = place::where('building_id', $building_id)->where('status', '!=', 0)->where('seat_type', 1)->count();
    $freecount = place::where('building_id', $building_id)->where('status', 1)->where('seat_type', 1)->count();
    $reservedcount = place::where('building_id', $building_id)->where('status', 2)->where('seat_type', 1)->count();
    $occupiedcount = place::where('building_id', $building_id)->where('status', 3)->where('seat_type', 1)->count();

    $thebuild = $this->getBuildingInfo($building_id);
    $loc_name = $thebuild->floor_name . '@' . $thebuild->building_name;

    return [
      'building_id' => $building_id,
      'building_name' => $loc_name,
      'total_seat' => $totalcount,
      'free_seat' => $freecount,
      'reserved_seat' => $reservedcount,
      'occupied_seat' => $occupiedcount
    ];
  }

  public function checkSeat($seat_id, $key = 'id'){
    // check if the seat is available
    $theseat = place::where($key, $seat_id)->first();
    if($theseat){
      if($theseat->status == 0){
        return $this->respond_json(401, 'seat disabled', $theseat);
      } elseif ($theseat->status == 2) {
        // reserved. check if still valid
        $time = new DateTime('NOW');
        $time->setTimezone(new DateTimeZone('+0800'));
        if($time < $theseat->reserve_expire){
          return $this->respond_json(401, 'seat reserved by others', $theseat);
        } else {
          // the reservation already expired. remove it
          $oldstaff = User::where('id', $theseat->reserve_staff_id)->first();
          $this->clearReservation($oldstaff);

          $theseat->reserve_expire = null;
          $theseat->reserve_staff_id = null;
          $theseat->save();
        }
      } elseif ($theseat->status == 3) {
        return $this->respond_json(401, 'seat occupied', $theseat);
      }

    } else {
      return $this->respond_json(404, 'seat not found', ['value' => $seat_id, 'key' => $key]);
    }

    // check building status
    $thebuildi = building::where('id', $theseat->building_id)->first();
    if($thebuildi->status == 0){
      return $this->respond_json(401, 'building disabled', $thebuildi);
    }

    // OK. proceed
    return $this->respond_json(200, 'Seat available', $theseat);
  }

  private function clearReservation($user){
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


}
