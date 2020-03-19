<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SkillCategory;
use App\SkillType;
use App\CommonSkillset;
use App\PersonalSkillset;
use App\PersSkillHistory;
use App\BauExperience;
use App\User;

class PersonalSSController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function listv2(Request $req){
    $sid = $req->user()->id;
    $isvisitor = false;
    $isboss = false;
    if($req->filled('staff_id')){
      if($sid != $req->staff_id){
        $isvisitor = true;
        $isboss = \App\common\UserRegisterHandler::isInReportingLine($req->staff_id, $sid);
        $sid = $req->staff_id;
      }
    }

    $user = User::find($sid);

    $skillcat = SkillCategory::all();
    $skilltype = SkillType::all();
    $skills = CommonSkillset::all();
    $perskill = PersonalSkillset::where('staff_id', $sid)->where('status', '!=', 'D')->get();
    $bauexps = $user->NoBauExperiences();

    return view('staff.skillset', [
      'user' => $user,
      'skillcat' => $skillcat,
      'skilltype' => $skilltype,
      'skills' => $skills,
      'pskills' => $perskill,
      'isvisitor' => $isvisitor,
      'isboss' => $isboss,
      'bes' => $bauexps
    ]);

  }

  private function addPersonalSkill($staff_id, $skill_id){
    $ps = PersonalSkillset::where('staff_id', $staff_id)->where('common_skill_id', $skill_id)->first();
    if(!$ps){
      $ps = new PersonalSkillset;
      $ps->common_skill_id = $skill_id;
      $ps->staff_id = $staff_id;
      $ps->save();
    }

    return $ps;
  }

  public function updatev2(Request $req){
    $newstatus = 'N';
    // double check
    if($req->user()->id != $req->staff_id){
      // added by someone else
      if(\App\common\UserRegisterHandler::isInReportingLine($req->staff_id, $req->user()->id)){
        // added by boss
        $newstatus = 'A';
      } else {
        // not validly added
        return redirect()->back()->with([
          'alert' => 'You are not allowed to add skill for this person',
          'a_type' => 'danger'
        ]);
      }
    }

    // check if the skill exists
    $ps = PersonalSkillset::where('staff_id', $req->staff_id)
      ->where('common_skill_id', $req->csid)
      ->where('status', '!=', 'D')->first();
    if($ps){
      return redirect()->back()->with([
        'alert' => 'Skill already added. Update it instead',
        'a_type' => 'warning'
      ]);
    }

    $ps = $this->addPersonalSkill($req->staff_id, $req->csid);
    $oldlevel = $ps->level ?? 0;
    $ps->level = $req->rate;
    $ps->status = $newstatus;
    $ps->save();

    // add the history
    $phist = new PersSkillHistory;
    $phist->personal_skillset_id = $ps->id;
    $phist->action_user_id = $req->user()->id;
    $phist->newlevel = $req->rate;
    $phist->oldlevel = $oldlevel;
    $phist->action = 'Add';
    $phist->remark = $req->remark;
    $phist->save();

    return redirect(route('ps.list', ['staff_id' => $req->staff_id]))
      ->with([
        'alert' => 'New skill added',
        'a_type' => 'success'
      ]);


  }


  public function detail(Request $req){
    if($req->filled('psid')){
      $ps = PersonalSkillset::find($req->psid);
      if($ps){
        // check who is the current user
        $sid = $req->user()->id;
        $isvisitor = false;
        $isboss = false;
        if($sid != $ps->staff_id){
          $isvisitor = true;
          $isboss = \App\common\UserRegisterHandler::isInReportingLine($ps->staff_id, $sid);
        }

        $owner = User::find($ps->staff_id);

        return view('staff.psdetail', [
          'ps' => $ps,
          'owner' => $owner,
          'isvisitor' => $isvisitor,
          'isboss' => $isboss
        ]);
      } else {
        return redirect(route('ps.list'));
      }


    } else {
      return redirect(route('ps.list'));
    }

  }

  public function modify(Request $req){
    $newstatus = 'C';

    $ps = PersonalSkillset::find($req->psid);
    if($ps){
      if($req->user()->id != $ps->staff_id){
        // added by someone else
        if(\App\common\UserRegisterHandler::isInReportingLine($ps->staff_id, $req->user()->id)){
          // updated by boss
          $newstatus = 'A';
        } else {
          // not validly added
          return redirect()->back()->with([
            'alert' => 'You are not allowed to add skill for this person',
            'a_type' => 'danger'
          ]);
        }
      }

      $oldlevel = $ps->level ?? 0;
      $newlevel = $req->rate;
      $haction = 'Update';

      if($req->action == 'A'){
        $newstatus = 'A';
        $haction = 'Approve';
      } elseif ($req->action == 'R') {
        $newstatus = 'R';
        $haction = 'Reject';
        $newlevel = 0;
      } elseif ($req->action == 'C') {
        if($newlevel == $oldlevel){
          $haction = "Comment";
          $newstatus = "x";
        } else {
          if($newstatus == 'C'){
            $haction = 'Update';
          } else {
            $haction = $newlevel < $oldlevel ? 'Downgraded' : "Upgraded";
          }
        }

      } elseif ($req->action == 'D') {
        $newstatus = 'D';
        $haction = 'Delete';
        $newlevel = 0;
      }

      if($newstatus != 'x'){
        $ps->level = $newlevel;
        if($newstatus == 'A'){
          $ps->prev_level = $newlevel;
        }

        $ps->status = $newstatus;
        $ps->save();
      }

      // add the history
      $phist = new PersSkillHistory;
      $phist->personal_skillset_id = $ps->id;
      $phist->action_user_id = $req->user()->id;
      $phist->newlevel = $newlevel;
      $phist->oldlevel = $oldlevel;
      $phist->action = $haction;
      $phist->remark = $req->remark;
      $phist->save();

      return redirect(route('ps.detail', ['psid' => $req->psid]))
        ->with([
          'alert' => 'Skill updated',
          'a_type' => 'success'
        ]);

    } else {
      return redirect(route('staff'));
    }

  }

  public function addexp(Request $req){

    if($req->user()->id != $req->uid){
      if(\App\common\UserRegisterHandler::isInReportingLine($req->uid, $req->user()->id)){
      } else {
        // added by someone not relevant
        return redirect()->back()->with([
          'alert' => 'You are not allowed to modify entry this person',
          'a_type' => 'danger'
        ]);
      }
    }

    $user = User::find($req->uid);
    if($user){
      $user->BauExperiences()->attach($req->beid);
      return redirect(route('ps.list', ['staff_id' => $req->uid]))
        ->with([
          'alert' => 'New experince added',
          'a_type' => 'success'
        ]);
    } else {
      return redirect(route('staff'));
    }
  }

  public function delexp(Request $req){

    if($req->user()->id != $req->uid){
      if(\App\common\UserRegisterHandler::isInReportingLine($req->uid, $req->user()->id)){
      } else {
        // added by someone not relevant
        return redirect()->back()->with([
          'alert' => 'You are not allowed to modify entry this person',
          'a_type' => 'danger'
        ]);
      }
    }

    $user = User::find($req->uid);
    if($user){
      $user->BauExperiences()->detach($req->beid);
      return redirect(route('ps.list', ['staff_id' => $req->uid]))
        ->with([
          'alert' => 'Experince removed',
          'a_type' => 'secondary'
        ]);
    } else {
      return redirect(route('staff'));
    }
  }

  public function pendingapprove(Request $req){
    dd($req->user()->report_to);
    $mypersno = $req->user()->persno;

    $subsids = User::where('report_to', $mypersno)->pluck('id');
    $pslist = PersonalSkillset::whereIn('staff_id', $subsids)
      ->whereIn('status', ['N', 'C'])->get();

    return view('staff.skillstaffpendapprove', [
      'pss' => $pslist
    ]);
  }

}
