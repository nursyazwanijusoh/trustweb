<?php

namespace App\common;

use App\User;
use App\SubUnit;
use App\LocationHistory;
use \Carbon\Carbon;


class TeamHelper
{

  public static function getStaffSubIDList($currentpersno){
    $stafflist = [];

    $ulist = User::where('report_to', $currentpersno)->where('status', 1)->get();
    foreach($ulist as $user){
      array_push($stafflist, $user->id);
      $thesubs = TeamHelper::getStaffSubIDList($user->persno);
      $stafflist = array_merge($stafflist, $thesubs);
    }

    return $stafflist;
  }

    public static function GetTeamLocInfo($currentuser, $idate){
      $resp = [];

      // memula dapatkan sendiri punya dulu
      $myloc = LocationHistory::where('user_id', $currentuser->id)
        ->whereDate('created_at', $idate)->orderBy('id', 'DESC')->first();

      $ltime = '';
      $lact = '';
      $laddr = '';
      $llat = '';
      $llong = '';
      $bgclass = '';
      $iscuti = false;
      $cutitype = '';
      $gotr = false;

      if($myloc){
        $ltime = $myloc->created_at;
        $lact = $myloc->action;
        $laddr = $myloc->address;
        $llat = $myloc->latitude;
        $llong = $myloc->longitude;
        $gotr = true;
      }

      // check cuti ke tak
      $nudp = GDWActions::GetDailyPerfObj($currentuser->id, $idate);
      if(isset($nudp->leave_type_id)){
        $bgclass = 'bg-info';
        $iscuti = true;
        $cutitype = $nudp->LeaveType->descr;
        if($lact == ''){
          $lact = $cutitype;
        }
      }

      $sendiri = [
        'id' => $currentuser->id,
        'staffno' => $currentuser->staff_no,
        'name' => $currentuser->name,
        'subunit' => $currentuser->subunit,
        'g' => $gotr,
        'ltime' => $ltime,
        'lact' => $lact,
        'laddr' => $laddr,
        'llat' => $llat,
        'llong' => $llong,
        'bg' => $bgclass,
        'iscuti' => $iscuti,
        'cutitype' => $cutitype
      ];

      array_push($resp, $sendiri);

      if(isset($currentuser->persno)){
        // then untuk setiap subs
        $subs = User::where('status', 1)
          ->where('report_to', $currentuser->persno)->get();

        foreach ($subs as $key => $value) {
          $subarr = TeamHelper::GetTeamLocInfo($value, $idate);
          // merge ngan main array
          $resp = array_merge($resp, $subarr);
        }
      }

      return $resp;

    }

    public static function GetTeamPerfInfo($ast, $start_date, $end_date){
      $dataarr = [];
      $daydiff = $end_date->diff($start_date)->days + 1;

      // update section ID, kalau band 3
      $sunitid = 0;
      if($ast->job_grade == '3'){
        // find my section id
        $subunit = SubUnit::where('ppsuborgunitdesc', $ast->subunit)->first();
        if($subunit){
          $sunitid = $subunit->id;
        }
        // update my section id
        $user->section_id = $sunitid;
        $user->save();
      }

      $daterange = new \DatePeriod(
        $start_date,
        \DateInterval::createFromDateString('1 day'),
        (new Carbon($end_date))->addDay()
      );

      // dapatkan sendiri punya dulu
      $perfarr = GDWActions::GetStaffRecentPerf($ast->id, $daterange);
      $perfavg = GDWActions::GetStaffAvgPerf($ast->id, $start_date, $end_date);
      array_push($dataarr, [
        'id' => $ast->id,
        'name' => $ast->name,
        'unit' => $ast->subunit,
        'recent_perf' => $perfarr,
        'avg' => $perfavg
      ]);

      // pastu dapatkan subs punya info, recursive
      if(isset($ast->persno)){
        // then find this person's subs
        $subarr = TeamHelper::getSubsPerfInfo($ast->persno, $daterange, $sunitid, $start_date, $end_date);
        $dataarr = array_merge($dataarr, $subarr);
      }

      // get average perf for alll
      $scount = 0;
      $tact = 0;
      $texp = 0;

      foreach($dataarr as $apers){
        $scount++;
        $tact += $apers['avg']['actual'];
        $texp += $apers['avg']['expected'];
      }

      if($texp == 0){
        $avgperf = $tact > 0 ? 100 + ($tact / (8 * $scount * $daydiff)) : 100;
      } else {
        $avgperf = $tact / $texp * 100;
      }

      return [
        's_name' => $ast->subunit,
        'daterange' => $daterange,
        'sdata' => $dataarr,
        'tavg' => $avgperf
      ];

    }

    public static function getSubsPerfInfo($persno, $daterange, $sunitid, $cdate, $ldate){
      $retarr = [];
      $staffs = User::where('report_to', $persno)->where('status', 1)->get();
      foreach($staffs as $ast){
        if($sunitid != 0){
          $ast->section_id = $sunitid;
          $ast->save();
        }

        // add this staff info

        $perfarr = GDWActions::GetStaffRecentPerf($ast->id, $daterange);
        $perfavg = GDWActions::GetStaffAvgPerf($ast->id, $cdate, $ldate);
        array_push($retarr, [
          'id' => $ast->id,
          'name' => $ast->name,
          'unit' => $ast->subunit,
          'recent_perf' => $perfarr,
          'avg' => $perfavg
        ]);

        if(isset($ast->persno)){
          // then find this person's subs
          $subarr = TeamHelper::getSubsPerfInfo($ast->persno, $daterange, $sunitid, $cdate, $ldate);
          $retarr = array_merge($retarr, $subarr);
        }
      }

      return $retarr;

    }

}
