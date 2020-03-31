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
      return "1 - Beginner";
    } elseif($this->level == 2){
      return "2 - Intermediate";
    } elseif ($this->level == 3) {
      return "3 - Expert";
    } elseif ($this->level == 0) {
      return "0 - N/A";
    }

    return $this->level . " - Unranked";
  }

  public function prev_approved(){
    if($this->prev_level == 1){
      return "1 - Beginner";
    } elseif($this->prev_level == 2){
      return "2 - Intermediate";
    } elseif ($this->prev_level == 3) {
      return "3 - Expert";
    } elseif ($this->prev_level == 0) {
      return "0 - N/A";
    }

    return $this->prev_level . " - Unranked";
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

  public function Histories(){
    return $this->hasMany(PersSkillHistory::class)->orderBy('created_at', 'DESC');
  }
}
