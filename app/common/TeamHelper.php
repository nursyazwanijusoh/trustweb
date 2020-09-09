<?php

namespace App\common;

use App\User;
use App\LocationHistory;


class TeamHelper
{

    public static function GetTeamLocInfo($currentuser, $idate){
      $resp = [];

      // memula dapatkan sendiri punya dulu
      $myloc = LocationHistory::where('user_id', $currentuser->id)
        ->whereDate('created_at', $idate)->orderBy('created_at', 'DESC')->first();

      $ltime = '';
      $lact = '';
      $laddr = '';
      $llat = '';
      $llong = '';
      $gotr = false;

      if($myloc){
        $ltime = $myloc->created_at;
        $lact = $myloc->action;
        $laddr = $myloc->address;
        $llat = $myloc->latitude;
        $llong = $myloc->longitude;
        $gotr = true;
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
        'llong' => $llong
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

}
