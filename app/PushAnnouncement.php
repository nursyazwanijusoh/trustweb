<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushAnnouncement extends Model
{
  public function Groups(){
    return $this->hasMany(PushAnnouncementGroup::class, 'push_announcement_id');
  }

  public function creator(){
    return $this->belongsTo(User::class, 'user_id');
  }
}
