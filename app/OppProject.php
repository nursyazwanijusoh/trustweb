<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OppProject extends Model
{
  public function Manager(){
    return $this->belongsTo(User::class, 'project_manager_id');
  }
}
