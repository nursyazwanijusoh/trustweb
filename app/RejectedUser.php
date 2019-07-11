<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RejectedUser extends Model
{
  public function Rejector(){
    return $this->belongsTo('App\User', 'rejected_by');
  }

  public function Partner(){
    return $this->belongsTo('App\Partner', 'partner_id');
  }
}
