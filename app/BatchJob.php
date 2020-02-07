<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchJob extends Model
{
  public function CGroup(){
    return $this->belongsTo(CompGroup::class, 'obj_id');
  }

  public function PushAnn(){
    return $this->belongsTo(PushAnnouncement::class, 'obj_id');
  }
}
