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

  public function NearEvent(){

    $stime = date('Y-m-d H:i:s', strtotime('30 minutes'));
    $now = date('Y-m-d H:i:s');

    return $this->hasMany('App\AreaEvent')->where('start_time', '<', $stime)
      ->where('end_time', '>=', $now)
      ->where('status', 'Active');
  }

}
