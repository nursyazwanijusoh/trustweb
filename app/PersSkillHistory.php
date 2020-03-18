<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersSkillHistory extends Model
{

  public function ActBy(){
    return $this->belongsTo(User::class, 'action_user_id');
  }

  public function GetAction(){
    if($this->action == 'Add'){
      return 'Added by ';
    } else {
      return $this->action . ' by ';
    }
  }
}
