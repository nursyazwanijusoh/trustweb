<?php

namespace App\common;

use Illuminate\Http\Request;
use \DateTime;
use \DateInterval;
use \DatePeriod;
use App\User;
use App\Unit;
use App\GwdActivity;
use \DB;

class GDWReports
{
  public static function getWorkdaysResult($field, $svalue, $fdate, $todate, $pporgunit){

    $unitid = Unit::where('pporgunit', $pporgunit)->first()->id;
    $searchlabel = $svalue;
    $totExptHours = 0;
    if($field == 'lob'){
      $unitv = Unit::where('pporgunit', $svalue)->first();
      if($unitv){
        $searchlabel = $unitv->pporgunitdesc;
      }
    }

    $finalout = [];
    $dateheader = [];
    array_push($dateheader, ['date' => 'Staff Name', 'isweekend' => 'd']);
    // first, get the list of date that we need to check
    $daterange = new DatePeriod(
      new DateTime($fdate),
      DateInterval::createFromDateString('1 day'),
      new DateTime($todate)
    );

    foreach($daterange as $onedate){
      if($onedate->format('w') == 0 || $onedate->format('w') == 6){
        $isweken = 'y';
      }
      else {
        $isweken = 'n';
        $totExptHours += 8;
      }

      $d = [
        'date' => $onedate->format('D d-m'),
        'isweekend' => $isweken
      ];

      array_push($dateheader, $d);
    }
    array_push($dateheader, ['date' => 'Total Hours', 'isweekend' => 'd']);

    // next, get the list of staff under the selection criteria
    $allstaffs = User::where($field, $svalue)
      ->orderBy('name', 'ASC')->get();
    // return $field;

    // so, for each staff
    foreach($allstaffs as $astaff){
      $sdata = [];
      $stotal = 0;
      // array_push($sdata, $astaff->name);
      // and for each day,
      foreach($daterange as $onedate){
        // $dsum = DB::table('activities')
        //   ->join('tasks', 'tasks.id', '=', 'activities.task_id')
        //   ->where('tasks.user_id', $astaff->id)
        //   ->where('activities.date', $onedate->format('Y-m-d'))
        //   ->sum('activities.hours_spent');

        if($astaff->isvendor == 0){
          $dsum = GwdActivity::where('user_id', $astaff->id)
            ->where('unit_id', $unitid)
            ->whereDate('activity_date', $onedate->format('Y-m-d'))
            ->sum('hours_spent');
        } else {
          $dsum = GwdActivity::where('user_id', $astaff->id)
            ->where('partner_id', $unitid)
            ->whereDate('activity_date', $onedate->format('Y-m-d'))
            ->sum('hours_spent');
        }

        array_push($sdata, $dsum);
        $stotal += $dsum;
      }
      array_push($sdata, $stotal);

      $onestaffdata = [
        'name' => $astaff->name,
        'staff_no' => $astaff->staff_no,
        'hours' => $sdata
      ];

      array_push($finalout, $onestaffdata);

    }

    return [
      'rlabel' => $searchlabel,
      'header' => $dateheader,
      'staffs' => $finalout,
      'expthours' => $totExptHours,
      'fromdate' => $fdate,
      'todate' => $todate
    ];

  }

}
