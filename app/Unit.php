<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB;

class Unit extends Model
{
  public function Staffs(){
    return $this->hasMany(User::class, 'lob', 'pporgunit')->where('status', 1);
  }

  public function StaffWithNotiID(){
    return $this->hasMany(User::class, 'lob', 'pporgunit')->where('status', 1)->whereNotNull('pushnoti_id');
  }

  public function Group(){
    return $this->belongsTo(CompGroup::class, 'comp_group_id');
  }

  public function PerfEntryOnDateRange($sdate, $edate){
    return $this->hasMany(DailyPerformance::class, 'unit_id', 'pporgunit')
      ->whereDate('record_date', '>=', $sdate)
      ->whereDate('record_date', '<=', $edate);
  }

  public function PerfEntrySummary($sdate, $edate){
    return DB::table('daily_performances')
      ->select('user_id',
        DB::raw('sum(expected_hours) as exp_hrs'),
        DB::raw('sum(actual_hours) as act_hrs'))
      ->groupBy('user_id')
      ->whereDate('record_date', '>=', $sdate)
      ->whereDate('record_date', '<=', $edate)
      ->where('unit_id', $this->pporgunit)
      ->get();
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
