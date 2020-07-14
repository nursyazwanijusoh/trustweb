<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgileResourceTeam extends Model
{
  public function User(){
    return $this->belongsTo(User::class);
  }
}
