<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
  public function poll(){
    return $this->belongsTo(Poll::class);
  }

  public function Users(){
    return $this->belongsToMany(User::class)->where('status', 1);
  }
}
