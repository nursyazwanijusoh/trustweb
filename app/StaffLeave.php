<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use App\common\GDWActions;

class StaffLeave extends Model
{
  public function LeaveType(){
    return $this->belongsTo(LeaveType::class, 'leave_type_id');
  }

  public function createCuti(){
    $sdate = new Carbon($this->start_date);
    $edate = new Carbon($this->end_date);
    $edate->addDay();

    while($edate->greaterThan($sdate)){
      $oned = GDWActions::GetDailyPerfObj($this->user_id, $sdate);
      if($oned->expected_hours > 0){
        // only set as leave if default expected is not 0
        $oned->is_off_day = true;
        $oned->leave_type_id = $this->leave_type_id;
        $oned->expected_hours = $this->LeaveType->hours_value;
        $oned->save();
      }

      $sdate->addDay();
    }
  }

  public function reverseCuti(){
    // GDWActions::GetExpectedHours($date)
    $sdate = new Carbon($this->start_date);
    $edate = new Carbon($this->end_date);
    $edate->addDay();

    while($edate->greaterThan($sdate)){
      $oned = GDWActions::GetDailyPerfObj($this->user_id, $sdate);
      $oned->is_off_day = false;
      $oned->leave_type_id = null;
      $oned->expected_hours = GDWActions::GetExpectedHours($sdate);
      $oned->save();

      $sdate->addDay();
    }
  }
}
