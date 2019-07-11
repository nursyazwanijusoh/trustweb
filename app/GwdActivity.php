<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GwdActivity extends Model
{
  public function User(){
    return $this->belongsTo('App\User', 'user_id');
  }

  public function Division(){
    if(isset($this->unit_id)){
      return $this->belongsTo('App\Unit', 'unit_id');
    } else {
      return $this->belongsTo('App\Partner', 'partner_id');
    }
  }

  public function ActType(){
    return $this->belongsTo('App\ActivityType', 'activity_type_id');
  }

}
