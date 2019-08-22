<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaEvent extends Model
{
  public function EventDay(){
    return $this->hasMany('App\EventAttendance', 'area_event_id');
  }

  public function Organizer(){
    return $this->belongsTo('App\User', 'organizer_id');
  }

  public function Location(){
    return $this->belongsTo('App\place', 'place_id');
  }

  public function EvenAttToday(){
    $now = date('Y-m-d');
    return $this->hasOne('App\EventAttendance', 'area_event_id')
      ->where('event_date', $now);
  }

}
