<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SkillCategory;
use App\SkillType;
use App\CommonSkillset;
use App\PersonalSkillset;
use App\PersSkillHistory;
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

    return view('staff.skillset', [
      'user' => $user,
      'skillcat' => $skillcat,
      'skilltype' => $skilltype,
      'skills' => $skills,
      'pskills' => $perskill,
      'isvisitor' => $isvisitor,
      'isboss' => $isboss
    ]);

  }

  public function list(Request $req){

    $sid = \Session::get('staffdata')['id'];
    if($req->filled('staff_id')){
      $sit = $req->staff_id;
    }

    $skillcatp = [];
    $skillcatm = [];

    $allcats = SkillCategory::orderBy('sequence', 'ASC')->get();

    foreach ($allcats as $key => $value) {
      // check if there is any skills under this category
      if($value->CommonSkillset->count() == 0){
        continue;
      }

      // load the skills of this category
      $sss = $value->CommonSkillset;
      $skillsp = [];
      $skillsm = [];

      foreach ($sss as $sk => $sv) {
        $curlvl = $sv->CurrentPS($sid);
        if($curlvl == -1){
          if($sv->category == 'm'){
            continue;
          } else {
            $curlvl = 0;
          }
        }

        $askil = [
          'id' => $sv->id,
          'name' => $sv->name,
          'current' => $curlvl
        ];

        if($sv->category == 'p'){
          array_push($skillsp, $askil);
        } else {
          array_push($skillsm, $askil);
        }

      }

      if(!empty($skillsp)){
        $outs = [
          'name' => $value->name,
          'skills' => $skillsp
        ];

        array_push($skillcatp, $outs);
      }

      if(!empty($skillsm)){
        $outm = [
          'name' => $value->name,
          'skills' => $skillsm
        ];

        array_push($skillcatm, $outm);
      }

    }

    if($req->filled('alert')){
      return view('staff.skillset', ['skillcatp' => $skillcatp, 'skillcatm' => $skillcatm, 'alert' => $req->alert]);
    }
    return view('staff.skillset', ['skillcatp' => $skillcatp, 'skillcatm' => $skillcatm]);
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
        $newstatus = 'E';
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
    $ps->level = $req->rate;
    $ps->status = $newstatus;
    $ps->save();

    // add the history
    $phist = new PersSkillHistory;
    $phist->personal_skillset_id = $ps->id;
    $phist->action_user_id = $req->user()->id;
    $phist->action = 'Add';
    $phist->remark = $req->remark;
    $phist->save();

    return redirect(route('ps.list', ['staff_id' => $req->staff_id]))
      ->with([
        'alert' => 'New skill added',
        'a_type' => 'success'
      ]);


  }

  public function update(Request $req){
    $sid = \Session::get('staffdata')['id'];
    $skills = $req->skill;

    foreach($skills as $ask){
      // check if this personal skill exist
      $ps = $this->addPersonalSkill($sid, $ask['id']);
      $ps->level = $ask['star'];
      $ps->save();

    }

    return redirect(route('ps.list', ['alert' => $req->cat . ' Skillset Updated'], false));

  }

  public function detail(Request $req){
    dd('Construction in progress');
  }

  public function createcustom(Request $req){
    $sid = \Session::get('staffdata')['id'];
    $msg = 'Skill ' . $req->name . ' created';

    // check if there's existing skill
    $exskill = CommonSkillset::where('name', $req->name)->first();
    if($exskill){
      $msg = 'Skill of same name already exist';
    } else {
      // create new if not exist
      $exskill = new CommonSkillset;
      $exskill->skill_category_id = $req->scat;
      $exskill->name = $req->name;
      $exskill->added_by = $sid;
      $exskill->category = 'm';
      $exskill->skillgroup = '';
      $exskill->skilltype = '';
      $exskill->save();

    }

    // add this skill
    $ps = $this->addPersonalSkill($sid, $exskill->id);

    return redirect(route('ps.add', ['alert' => $msg], false));
  }

  public function addcustom(Request $req){
    $sid = \Session::get('staffdata')['id'];
    $scat = SkillCategory::all();
    // only list skill that is not yet added
    $knownskills = PersonalSkillset::where('staff_id', $sid)
      ->select('common_skill_id')->get()->toArray();
    $sklist = CommonSkillset::where('category', 'm')->whereNotIn('id', $knownskills)->get();

    return view('staff.addcustomskill', ['sklist' => $sklist, 'cats' => $scat]);
  }

  public function doaddcustom(Request $req){
    $sid = \Session::get('staffdata')['id'];
    if(!$req->filled('skill_id')){
      return redirect(route('ps.add', ['alert' => 'Skill ID required'], false));
    }

    $skill = CommonSkillset::findOrFail($req->skill_id);
    $ps = $this->addPersonalSkill($sid, $skill->id);
    return redirect(route('ps.add', ['alert' => 'Skill ' . $skill->name . ' added'], false));
  }

}
