<?php

namespace App\common;

use App\McoTravelReq;
use App\Notifications\McoPermitApplied;
use App\Notifications\McoPermitApproved;
use App\Notifications\McoPermitRejected;
use App\User;
use \Carbon\Carbon;

class McoActions {

  public static function SubmitApplication($staff_id, $location, $reqdate, $reason, $gmid = 0){
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

      if($gmid == 0){
        $mygm = McoActions::FindAtLeastGm($user, $user);
      } else {
        if(UserRegisterHandler::isInReportingLine($staff_id, $gmid)){
          $mygm = User::find($gmid);
        } else {
          return 'Not a valid approver';
        }
      }

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

  public static function TakeActionAllMine($approver_id, $status){
    $nt = new Carbon();

    if($status == 'Approved' || $status == 'Rejected'){
      $mcolist = McoTravelReq::where('approver_id', $approver_id)
        ->where('status', 'Pending Approval')->get();

      foreach($mcolist as $mco){

        $mco->status = $status;
        $mco->action_datetime = $nt;
        $mco->save();

        // send the notification
        if($status == 'Approved'){
          $mco->requestor->notify(new McoPermitApproved($mco));
        } else {
          $mco->requestor->notify(new McoPermitRejected($mco));
        }
      }
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

  public static function FindAtLeastGm($user, $origin_user){

    if(isset($user->report_to)){
      // check the boss of this person
      $myboss = $user->Boss;
      if($myboss){
        // update fromm ldap

        $myboss->job_grade = McoActions::GetJobGrade($myboss->staff_no);
        $myboss->save();

        if($myboss->job_grade == 5){
          // already reached VP / C
          if($user->id == $origin_user->id){
            // legit case report to band 5
            return $myboss;
          } else {
            // return the person before this band 5;
            return $user;
          }
        } elseif($myboss->job_grade == 4) {
          return $myboss;
        } else {
          // climb further
          return McoActions::FindAtLeastGm($myboss, $origin_user);
        }
      } else {
        // no boss. return self
        return $user;
      }
    } else {
      // no boss. return self
      return $user;
    }
  }

  public static function GetJobGrade($staff_no){
    $adminuser = env('TMLDAP_ADMINUSER');
    $udn= "cn=$adminuser, ou=serviceAccount, o=Telekom";
    $password = env('TMLDAP_ADMINPASS');
    $hostnameSSL = env('TMLDAP_HOSTNAME');
    $retdata = 1;
    //	ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
    putenv('LDAPTLS_REQCERT=never');

    $con =  ldap_connect($hostnameSSL);
    if (is_resource($con)){
      if (ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3)){
        ldap_set_option($con, LDAP_OPT_REFERRALS, 0);

        // try to bind / authenticate
        try{
        if (ldap_bind($con,$udn, $password)){

          // perform the search
          $ldres = ldap_search($con, 'ou=users,o=data', "cn=" . $staff_no);
          $ldapdata = ldap_get_entries($con, $ldres);
          // dd($ldapdata);


          if($ldapdata['count'] > 0){
            if(isset($ldapdata['0']['ppjobgrade']['0'])){
              $retdata = $ldapdata['0']['ppjobgrade']['0'];
            } else {
              $retdata = 'X';
            }

          }

        } else {
          $errorcode = 403;
          $errm = 'Invalid admin credentials.';
        }} catch(Exception $e) {
          $errorcode = 500;
          $errm = $e->getMessage();
        }

      } else {
        $errorcode = 500;
        $errm = "TLS not supported. Unable to set LDAP protocol version to 3";
      }

      // clean up after done
      ldap_close($con);

    } else {
      $errorcode = 500;
      $errm = "Unable to connect to $hostnameSSL";
    }

    return $retdata;
  }

  public static function GetApprovers($user){
    $ret = [];
    // first, find the default approver
    $def = McoActions::FindAtLeastGm($user, $user);
    array_push($ret, [
      'id' => $def->id,
      'name' => $def->name,
      'pos' => 'Default Approver'
    ]);

    // then start crawl up
    while(isset($def->report_to)){
      $def = User::where('persno', $def->report_to)->first();
      if($def){
        $def->job_grade = McoActions::GetJobGrade($def->staff_no);
        $def->save();

        array_push($ret, [
          'id' => $def->id,
          'name' => $def->name,
          'pos' => $def->position
        ]);

      } else {
        // report to not found
        break;
      }

    }

    return $ret;

  }

}
