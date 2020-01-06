<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyPerformance extends Model
{
  public function Activities(){
    return $this->hasMany(GwdActivity::class, 'daily_performance_id');
  }

  public function LeaveType(){
    return $this->belongsTo(LeaveType::class, 'leave_type_id');
  }

  public function User(){
    return $this->belongsTo(User::class, 'user_id');
  }

  public function addHours($hours){
    $this->actual_hours +=  $hours;
    $this->save();
  }

  public function addLeave(StaffLeave $leave){

  }

  public function removeLeave(){

  }
}
