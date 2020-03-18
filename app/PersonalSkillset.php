<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalSkillset extends Model
{
  public function CommonSkill(){
    return $this->belongsTo(CommonSkillset::class, 'common_skill_id');
  }

  public function User(){
    return $this->belongsTo(User::class, 'staff_id');
  }

  public function slevel(){
    if($this->level == 1){
      return "Beginner";
    } elseif($this->level == 2){
      return "Intermediate";
    } elseif ($this->level == 3) {
      return "Expert";
    } elseif ($this->level == 0) {
      return "Deleted";
    }

    return $this->level . " - Unranked";
  }

  public function sStatus(){
    if($this->status == "N"){
      return "New";
    } elseif($this->status == "A"){
      return "Approved";
    } elseif ($this->status == "C") {
      return "Changed";
    } elseif ($this->status == "R") {
      return "Rejected";
    } elseif ($this->status == "D") {
      return "Deleted";
    }
  }
}
