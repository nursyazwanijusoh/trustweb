<?php

namespace App\common;

use App\McoTravelReq;
use App\Notifications\McoPermitApplied;
use App\Notifications\McoPermitApproved;
use App\Notifications\McoPermitRejected;
use App\User;
use \Carbon\Carbon;

class McoActions {

  public static function SubmitApplication($staff_id, $location, $reqdate, $reason){
    // double check date dulu
    $date = new Carbon($reqdate);
    $today = new Carbon;

    if($date->toDateString() == $today->toDateString()){
      // hari yg sama. accept
    } else {
      $today->addDay();
      if($date->toDateString() == $today->toDateString()){
        // hari esok. accept gak
      } else {
        // bukan harini atau esok. reject
        return 'Not an acceptable date';
      }
    }

    // check duplicate
    $dup = McoTravelReq::where('request_date', $date)
      ->where('requestor_id', $staff_id)
      ->whereIn('status', ['Pending Approval', 'Acknowledged'])
      ->first();

    if($dup){
      return 'Got other request with same date';
    }

    $user = User::find($staff_id);
    if($user){
      $mygm = McoActions::FindAtLeastGm($user);

      // register the request
      $mco = new McoTravelReq;
      $mco->request_date = $reqdate;
      $mco->requestor_id = $user->id;
      $mco->approver_id = $mygm->id;
      $mco->location = $location;
      $mco->reason = $reason;
      $mco->unit_id = $user->unit_id;
      $mco->status = 'Pending Approval';
      $mco->save();

      // if self is gm
      if($user->id == $mygm->id){
        $mco->status = 'Approved';
        $mco->save();
      } else {
        $mygm->notify(new McoPermitApplied($mco));
      }

    } else {
      return 'User not found';
    }

    // success. return 'Success'
    return 200;
  }

  public static function ApproveApplication($mco_req_id, $approver_id){
    $nt = new Carbon();
    $mco = McoTravelReq::find($mco_req_id);
    if($mco){
      // check if it's the actual approver
      if($mco->approver_id == $approver_id){
        if($mco->status == 'Pending Approval'){
          $mco->status = 'Approved';
          $mco->action_datetime = $nt;
          $mco->save();

          // send the notification
          $mco->requestor->notify(new McoPermitApproved($mco));
        }

        return 200;

      } else {
        return 403;
      }
    } else {
      return 404;
    }
  }

  public static function RejectApplication($mco_req_id, $approver_id){
    $nt = new Carbon();
    $mco = McoTravelReq::find($mco_req_id);
    if($mco){
      // check if it's the actual approver
      if($mco->approver_id == $approver_id){
        if($mco->status == 'Pending Approval'){
          $mco->status = 'Rejected';
          $mco->action_datetime = $nt;
          $mco->save();

          // send the notification
          $mco->requestor->notify(new McoPermitRejected($mco));
        }

        return 200;

      } else {
        return 403;
      }
    } else {
      return 404;
    }
  }

  public static function FindAtLeastGm($user){

    if($user){
    } else {
      return "no";
    }

    if($user->job_grade == 4 || $user->job_grade == 5) {
      return $user;
    }

    if(isset($user->report_to)){
      $nom = McoActions::FindAtLeastGm($user->Boss);
      if($nom == 'no'){
        return $user;
      }

      return $nom;
    } else {
      return $user;
    }



  }

}
