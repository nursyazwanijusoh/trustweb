<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushAnnouncementGroup extends Model
{
  public function TheGroup(){
    return $this->belongsTo(CompGroup::class, 'group_id', 'id');
  }

  public function Divisions(){
    return $this->TheGroup->Members;
  }
}
