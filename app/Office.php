<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
  public function building(){
    return $this->hasMany('App\building');
  }

  public function creator(){
    return $this->hasOne('App\User', 'created_by');
  }

  public function editor(){
    return $this->hasOne('App\User', 'modified_by');
  }
}
