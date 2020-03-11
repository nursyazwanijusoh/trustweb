<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonSkillset extends Model
{
  public function SkillCategory(){
    return $this->belongsTo('App\SkillCategory', 'skill_category_id');
  }

  public function SkillType(){
    return $this->belongsTo(SkillType::class, 'skill_type_id');
  }

  public function PersonalSkillset(){
    return $this->hasMany('App\PersonalSkillset', 'common_skill_id')->where('level','!=', 0);
  }

  public function CurrentPS($staff_id){
    $myps = $this->PersonalSkillset()->where('staff_id', $staff_id)->first();

    if($myps){
      return $myps->level;
    }

    return -1;
  }

  public function creator(){
    return $this->belongsTo('App\User', 'added_by');
  }

}
