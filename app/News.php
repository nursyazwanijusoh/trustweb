<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
  use SoftDeletes;

  public function Creator(){
    return $this->belongsTo(User::class, 'user_id');
  }
}
