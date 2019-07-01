<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkillCategory extends Model
{
  public function CommonSkillset(){
    return $this->hasMany('App\CommonSkillset');
  }

  public function creator(){
    return $this->belongsTo('App\User', 'added_by');
  }

}
