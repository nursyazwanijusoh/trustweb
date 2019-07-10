<?php

namespace App\common;

use Illuminate\Http\Request;
use App\User;
use App\GwdActivity;
use \DB;

class GDWActions
{
  public static function addActivity(Request $req){

    $act = new GwdActivity;
    $act->user_id = $req->staff_id;
    $act->activity_type_id = $req->act_type;
    $act->title = $req->title;
    $act->hours_spent = $req->hours_spent;

    // optionals
    if($req->filled('parent_no')){
      $act->parent_number = $req->parent_no;
    }

    if($req->filled('details')){
      $act->details = $req->details;
    }

    if($req->filled('act_date')){
      $act->activity_date = $req->act_date;
    } else {
      $act->activity_date = date('Y-m-d');
    }

    $user = $act->User;

    if($user->isvendor == 1){
      $act->partner_id = $user->partner_id;
    } else {
      $act->unit_id = $user->unit_id;
    }

    // get current checkin as well
    $act->checkin_id = $user->curr_checkin;
    $act->save();

    return $act;

  }
}
