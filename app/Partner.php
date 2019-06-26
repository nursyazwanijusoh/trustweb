<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
  public function verifyUser(){
    return $this->hasMany('App\User');
  }
}
