<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
  public function Attendee(){
    return $this->hasMany('App\Checkin', 'event_attendance_id');
  }

  public function AreaEvent(){
    return $this->belongsTo('App\AreaEvent', 'area_event_id');
  }
}
