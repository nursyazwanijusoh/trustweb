<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SkillCategory;

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
        $askil = [
          'id' => $sv->id,
          'name' => $sv->name,
          'current' => $curlvl
        ];

        if($sv->category == 'p'){
          array_push($skillsp, $askil);
        } else {
          if($curlvl > 0){
            array_push($skillsm, $askil);
          }
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

    // $skillcatm = SkillCategory::where('category', 'm')->orderBy('sequence', 'ASC')->get();

    // $skillcatp = [
    //   [ 'name' => 'Dev',
    //     'skills' => [
    //       ['id' => 1, 'name' => 'bercakap', 'current' => 2],
    //       ['id' => 2, 'name' => 'menaip', 'current' => 5],
    //     ]],
    //   [ 'name' => 'Soft',
    //     'skills' => [
    //       ['id' => 6, 'name' => 'Jawa', 'current' => 0],
    //       ['id' => 4, 'name' => 'Banjar', 'current' => 1],
    //     ]],
    // ];

    return view('staff.skillset', ['skillcatp' => $skillcatp, 'skillcatm' => $skillcatm]);
  }

  public function update(Request $req){
    dd($req->all());
  }
}
