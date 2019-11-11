<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffLeave extends Model
{
  public function LeaveType(){
    return $this->belongsTo(LeaveType::class, 'leave_type_id');
  }
}
