<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  public function Staffs(){
    return $this->hasMany(User::class, 'lob', 'pporgunit');
  }

  public function Group(){
    return $this->belongsTo(CompGroup::class, 'comp_group_id');
  }

  public function PerfEntryOnDate($date){
    return $this->hasMany(DailyPerformance::class, 'unit_id', 'pporgunit')
      ->whereDate('record_date', $date);
  }
}
