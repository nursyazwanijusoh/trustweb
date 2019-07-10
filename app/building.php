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

  public function MeetingRooms(){
    return $this->hasMany('App\place')->where('seat_type', 2);
  }
}
