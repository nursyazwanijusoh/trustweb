<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersSkillHistory extends Model
{

  public function ActBy(){
    return $this->belongsTo(User::class, 'action_user_id');
  }

  public function GetPreText(){
    if($this->action == 'Add'){
      return 'Added by ';
    } elseif($this->action == 'Comment') {
      return "" ;
    } elseif($this->action == 'Reject') {
      return "Rejected by" ;
    } elseif($this->action == 'Delete') {
      return "" ;
    } elseif($this->action == 'Update') {
      return "Updated by" ;
    } elseif($this->action == 'Approve') {
      return "Approved by" ;
    } else {
      return $this->action . ' by ';
    }
  }

  public function GetPostText(){
    if($this->action == 'Add'){
      return "with rating <b>".$this->nlevel()."</b>" ;
    } elseif($this->action == 'Comment') {
      return "added comment" ;
    } elseif($this->action == 'Reject') {
      return "" ;
    } elseif($this->action == 'Delete') {
      return "deleted this skill" ;
    } elseif($this->action == 'Approve') {
      return "with level <b>" . $this->nlevel() . "</b>" ;
    } else {
      return "from <b>".$this->olevel()."</b> to <b>" . $this->nlevel() . "</b>" ;
    }
  }

  public function nlevel(){
    if($this->newlevel == 1){
      return "Beginner";
    } elseif($this->newlevel == 2){
      return "Intermediate";
    } elseif ($this->newlevel == 3) {
      return "Expert";
    } elseif ($this->newlevel == 0) {
      return "Deleted";
    }

    return $this->newlevel . " - Unranked";
  }

  public function olevel(){
    if($this->oldlevel == 1){
      return "Beginner";
    } elseif($this->oldlevel == 2){
      return "Intermediate";
    } elseif ($this->oldlevel == 3) {
      return "Expert";
    } elseif ($this->oldlevel == 0) {
      return "Deleted";
    }

    return $this->oldlevel . " - Unranked";
  }
}
