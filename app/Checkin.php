<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
  public function place(){
    return $this->belongsTo('App\place', 'place_id');
  }
}
