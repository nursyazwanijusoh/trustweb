<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
  public function options(){
    return $this->hasMany(PollOption::class);
  }

  public function Users(){
    return $this->belongsToMany(User::class)->where('status', 1);
  }

  public function Owner(){
    return $this->belongsTo(User::class, 'user_id');
  }

}
