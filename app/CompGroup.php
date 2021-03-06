<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompGroup extends Model
{
  use SoftDeletes;

  public function Members(){
    return $this->hasMany(Unit::class, 'comp_group_id');
  }

  public function StaffCount(){
    $csum = 0;

    foreach($this->Members as $aunit){
      $csum += User::where('lob', $aunit->pporgunit)->where('status', 1)->count();
    }

    return $csum;
  }

  // the HR reps
  public function Users(){
    return $this->belongsToMany(User::class);
  }

}
