<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class place extends Model
{
  public function building(){
    return $this->belongsTo('App\building', 'building_id');
  }

  public function Checkin(){
    return $this->hasMany('App\Checkin')->whereNull('checkout_time');
  }

  public function Occupant(){
    return $this->belongsTo('App\User', 'checkin_staff_id');
  }

}
