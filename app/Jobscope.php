<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobscope extends Model
{
  public function involvements(){
    return $this->belongsToMany(Involvement::class);
  }
}
