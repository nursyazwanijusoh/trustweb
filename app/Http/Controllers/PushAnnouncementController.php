<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PushAnnouncement;
use App\PushAnnouncementGroup;
use App\CompGroup;
use App\common\NotifyHelper;
use App\User;

class PushAnnouncementController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function showForm(){
    // get list of groups
    $grplist = CompGroup::all();

    return view('admin.blastnoti', [
      'group' => $grplist
    ]);
  }

  public function registerReq(Request $req){

    $pa = new PushAnnouncement;
    $pa->user_id = $req->user()->id;
    $pa->title = $req->title;
    $pa->body = $req->body;
    $pa->save();


    if($req->isglobal == 'true'){
      $pa->is_global = true;
      $pa->save();
    } else {
      foreach ($req->grplist as $value) {
        $pag = new PushAnnouncementGroup;
        $pag->push_announcement_id = $pa->id;
        $pag->group_id = $value;
        $pag->save();
      }
    }

    return redirect(route('pn.form', [], false))->with(['pn_id' => $pa->id]);
  }

  public function doSend(Request $req){
    set_time_limit(0);
    $status = 'Failed';
    $count = 0;
    $msg = '';

    if($req->filled('pn_id')){
      $pn = PushAnnouncement::find($req->pn_id);

      if($pn){
        // check for actual owner
        if($pn->user_id == $req->user()->id){

          if($pn->status == 'N'){

            $pn->status = 'P';
            $pn->save();

            if($pn->is_global){
              // send for everyone
              $stafflist = User::whereNotNull('pushnoti_id')->where('status', 1)->get();
              $psize = 0;
              $idlist = [];
              foreach($stafflist as $onestaff){
                if(strlen(trim($onestaff->pushnoti_id)) > 8){
                  array_push($idlist, $onestaff->pushnoti_id);
                  $count++;
                  $psize++;

                  if($psize == 50){
                    $psize = 0;
                    $aaa = NotifyHelper::SendPushNoti($idlist, $pn->title, $pn->body);
                    $idlist = [];
                  }
                }
              }

              if($psize > 0){
                $psize = 0;
                $aaa = NotifyHelper::SendPushNoti($idlist, $pn->title, $pn->body);
                $idlist = [];
              }
            } else {
              foreach ($pn->Groups as $grp) {
                // dd($grp->TheGroup);
                foreach($grp->Divisions() as $ondiv){
                  $stafflist = $ondiv->StaffWithNotiID;
                  $psize = 0;
                  $idlist = [];

                  foreach($stafflist->all() as $onestaff){
                    if(strlen(trim($onestaff->pushnoti_id)) > 8 && $onestaff->status = 1){
                      array_push($idlist, $onestaff->pushnoti_id);
                      $count++;
                      $psize++;

                      if($psize == 50){
                        $psize = 0;
                        $aaa = NotifyHelper::SendPushNoti($idlist, $pn->title, $pn->body);
                        $idlist = [];
                      }
                    }
                  }
                  if($psize > 0){
                    $psize = 0;
                    $aaa = NotifyHelper::SendPushNoti($idlist, $pn->title, $pn->body);
                    $idlist = [];
                  }
                }
              }
            }

            $status = 'Completed';
            $msg = 'Notification sent';

            $pn->status = 'C';
            $pn->rec_count = $count;
            $pn->save();
          } else {
            $msg = 'Notification already sent';
          }



        } else {
          $msg = 'You are not the owner of this announcement';
        }
      } else {
        $msg = 'pn_id 404';
      }
    } else {
      $msg = 'Missing pn_id';
    }

    return [
      'status' => $status,
      'msg' => $msg,
      'rcount' => $count
    ];
  }
}
