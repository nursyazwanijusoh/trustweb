<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OppAssigment extends Model
{
    public function project(){
        return $this->belongsTo(OppProject::class, 'proj_id');
      }

      public function user(){
        return $this->belongsTo(User::class, 'user_id');
      }


}
