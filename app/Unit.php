<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  public function Staffs(){
    return $this->hasMany('App\User');
  }
}
