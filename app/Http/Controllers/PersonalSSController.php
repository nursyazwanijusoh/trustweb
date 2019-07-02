<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SkillCategory;
use App\CommonSkillset;
use App\PersonalSkillset;

class PersonalSSController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
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
