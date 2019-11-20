<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class building extends Model
{
  public function office(){
    return $this->belongsTo('App\Office', 'office_id');
  }

  public function place(){
    return $this->hasMany('App\place')->where('seat_type', 1);
  }

  public function bookable(){
    return $this->hasMany('App\place')->where('seat_type', 1)->where('bookable', true);
  }

  public function MeetingRooms(){
    return $this->hasMany('App\place')->where('seat_type', 2);
  }

  public function Asset($type, $canbook = 0){
    if($type == 1){
      if($canbook == 0){
        $asset = $this->place;
      } else {
        $asset = $this->place->where('bookable', true);
      }

      unset($this['place']);
    } else {
      $asset = $this->MeetingRooms;
      unset($this['MeetingRooms']);
    }

    return $asset;
  }
}
