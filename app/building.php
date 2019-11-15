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

  public function Asset($type){
    if($type == 1){
      $asset = $this->place;
      unset($this['place']);
    } else {
      $asset = $this->MeetingRooms;
      unset($this['MeetingRooms']);
    }

    return $asset;
  }
}
