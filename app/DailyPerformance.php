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

  public function Division(){
    return $this->belongsTo(Unit::class, 'unit_id', 'pporgunit');
  }

  public function User(){
    return $this->belongsTo(User::class, 'user_id');
  }

  public function PublicHoliday(){
    return $this->belongsTo(PublicHoliday::class);
  }

  public function getCutiInfo(){
    if($this->is_public_holiday){
      return $this->PublicHoliday->name;
    } elseif($this->is_off_day){
      return $this->LeaveType->descr;
    } else {
      return '';
    }
  }

  public function addHours($hours){
    $this->actual_hours +=  $hours;

    // $this->actual_hours = $this->Activities->sum('hours_spent');

    $this->save();
  }

  public function recalcHours(){
    $this->actual_hours = GwdActivity::where('daily_performance_id', $this->id)->sum('hours_spent');
    $this->save();
  }

  public function addLeave(StaffLeave $leave){

  }

  public function removeLeave(){

  }
}
