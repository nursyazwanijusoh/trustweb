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

  public function PerfEntryOnDateRange($sdate, $edate){
    return $this->hasMany(DailyPerformance::class, 'unit_id', 'pporgunit')
      ->whereDate('record_date', '>=', $sdate)
      ->whereDate('record_date', '<=', $edate);
  }

  public function shortName(){
    $nlist = explode(" ", $this->pporgunitdesc);
    $sf = "";
    foreach ($nlist as $key => $value) {
      if(strlen($value > 0)){
        $sf = $sf . substr($value, 0, 1);
      }
    }

    return $sf;
  }
}
