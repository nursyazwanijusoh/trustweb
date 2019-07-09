<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
  public function Sender(){
    return $this->belongsTo('App\User', 'staff_id');
  }

  public function Closer(){
    return $this->belongsTo('App\User', 'closed_by');
  }

  public function getContact(){
    if($this->staff_id == 0){
      return $this->contact;
    } else {
      return $this->Sender->email;
    }
  }
}
