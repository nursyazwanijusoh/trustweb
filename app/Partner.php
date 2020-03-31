<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
  public function Users(){
    return $this->hasMany('App\User')->where('status', 1);
  }
}
