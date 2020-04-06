<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\common\IopHandler;
use App\User;
use App\StaffPersMap;
use App\SapEmpProfile;
use App\SapLeaveInfo;
use App\BulkSkillsetAdd;
use App\CommonSkillset;
use App\SkillType;
use App\SkillCategory;
use App\PersonalSkillset;
use App\PersSkillHistory;
use App\common\BatchHelper;

class SapLoadController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function showSummaryPage(Request $req){
    $emp = SapEmpProfile::where('load_status', 'N')->count();
    $cuti = SapLeaveInfo::where('load_status', 'N')->count();
    $skill = BulkSkillsetAdd::where('load_status', 'N')->count();

    return view('admin.sapdash', [
      'eplist' => $emp,
      'skillcount' => $skill,
      'cuticount' => $cuti
    ]);
  }

  public function loadBulkSkillset(Request $req){
    set_time_limit(0);
    $list = BulkSkillsetAdd::where('load_status', 'N')->get();

    foreach($list as $u){

      // find the user
      $yser = User::where('staff_no', $u->staff_no)->first();
      if($yser){
        // then find the skill
        $cskill = CommonSkillset::where('name', $u->skill)->first();
        if($cskill){
          // check user ni pernah ada skill ni tak
          $ps = PersonalSkillset::where('staff_id', $yser->id)
            ->where('common_skill_id', $cskill->id)->first();

          if($ps){
            // dah pernah ada
            $u->load_status = 'D';
            $u->save();
          } else {
            // create baru
            $ps = new PersonalSkillset;
            $ps->common_skill_id = $cskill->id;
            $ps->staff_id = $yser->id;
            $ps->level = $u->level;
            $ps->prev_level = $u->level;
            $ps->status = 'M';
            $ps->save();

            // tambah dalam PersSkillHistory
            $phist = new PersSkillHistory;
            $phist->personal_skillset_id = $ps->id;
            $phist->action_user_id = $req->user()->id;
            $phist->newlevel = $u->level;
            $phist->oldlevel = 0;
            $phist->action = 'Migrate';
            $phist->remark = $u->remark;
            $phist->save();

            $u->load_status = 'S';
            $u->save();

          }

        } else {
          $u->load_status = 'X';
          $u->save();
        }
      } else {
        $u->load_status = 'O';
        $u->save();
      }
    }
  }

  public function processOM(Request $req){
    set_time_limit(0);
    BatchHelper::loadOMData();

  }

  public function loadDataCuti(Request $req){
    set_time_limit(0);
    BatchHelper::loadCutiData($req->user()->id);

  }
}
