<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class place extends Model
{
  public function building(){
    return $this->belongsTo('App\building', 'building_id');
  }

  public function Checkin(){
    return $this->hasMany('App\Checkin');
  }

}
