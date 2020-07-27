<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OppProject extends Model {
    public function assigments() {
        return $this->hasMany( OppAssigment::class, 'proj_id','id' )->orderBy( 'created_at', 'DESC' );
    }

    public function Manager(){
      return $this->belongsTo(User::class, 'lead_by');
    }
}
