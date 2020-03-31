<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Involvement extends Model
{
  public function roles(){
    return $this->belongsToMany(Jobscope::class);
  }

  public function BauExp(){
    return $this->belongsTo(BauExperience::class, 'bau_experience_id');
  }

  public function User(){
    return $this->belongsTo(User::class);
  }


}
