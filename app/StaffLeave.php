<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use App\common\GDWActions;

class StaffLeave extends Model
{

  protected $fillable = ['user_id', 'start_date', 'end_date', 'leave_type_id'];

  public function LeaveType(){
    return $this->belongsTo(LeaveType::class, 'leave_type_id');
  }

  public function createCuti(){
    $sdate = new Carbon($this->start_date);
    $edate = new Carbon($this->end_date);
    $edate->addDay();

    while($edate->greaterThan($sdate)){
      $oned = GDWActions::GetDailyPerfObj($this->user_id, $sdate);
      if($oned->zerorized == true){
        // do nothing if zerorized
      } else {
        if($oned->expected_hours > 0){
          // only set as leave if default expected is not 0
          $oned->is_off_day = true;
          $oned->leave_type_id = $this->leave_type_id;

          if($this->LeaveType->hours_value != 8){
            $oned->expected_hours = $this->LeaveType->hours_value;
          }

          $oned->save();
        }
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

      $user = User::find($this->user_id);
      $friday = $user->Division->friday_hours;

      $oned = GDWActions::GetDailyPerfObj($this->user_id, $sdate);
      if($oned->zerorized == true){
        // dont do anything if this day is zerorized
      } else {
        $oned->is_off_day = false;
        $oned->leave_type_id = null;
        $oned->expected_hours = GDWActions::GetExpectedHours($sdate, $oned, null, $friday);
        $oned->save();
      }


      $sdate->addDay();
    }
  }
}
