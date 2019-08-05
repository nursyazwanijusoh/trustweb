<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
  public function getDiv(){
    if($this->isvendor == 1){
      return $this->Partner->comp_name;
    } else {
      return $this->Division->pporgunitdesc;
    }
  }

  public function Partner(){
    return $this->belongsTo('App\Partner', 'division_id');
  }

  public function Division(){
    return $this->belongsTo('App\Unit', 'division_id');
  }
}
