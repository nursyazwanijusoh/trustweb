<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GwdActivity extends Model
{
  public function User(){
    return $this->belongsTo('App\User', 'user_id');
  }

  public function Division(){
    return $this->belongsTo('App\User', 'user_id');
  }

}
