<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OppApplication extends Model
{
    public function assigment(){
        return $this->belongsTo(OppAssignment::class, 'assign_id');
      }

      public function user(){
        return $this->belongsTo(User::class, 'user_id');
      }
}
