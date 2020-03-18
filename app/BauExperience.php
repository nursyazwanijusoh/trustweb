<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BauExperience extends Model
{
  use SoftDeletes;

  public function Users(){
    return $this->belongsToMany(User::class);
  }
}
