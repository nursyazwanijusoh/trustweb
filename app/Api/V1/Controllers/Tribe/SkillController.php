<?php

namespace App\Api\V1\Controllers\Tribe;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\CommonSkillset;
use App\PersonalSkillset;

class SkillController extends Controller
{
    public function getSkills(Request $req)
    {
        $skillset = CommonSkillset::all('id', 'name');
        $competency = [
         ['id'=> 1, 'descr'=>'noob'],
         ['id'=>2, 'descr'=>'soso'],
         ['id'=>3, 'descr'=>'expert']];

        $result = [
         'competency'=>$competency,
         'skillset'=>$skillset
         
      ];


        return $result;
    }
    public function getUsersBySkills(Request $req)
    {
        $users = User::query();
  

        $skills = $req->skills;
        /*
        foreach ($skills as $key => $value) {
         $users->whereIn('id', PersonalSkillset::where('common_skill_id', $value['skill'])->where('level', '=', $value['lvl'])->pluck('staff_id'));
     }
     */

        foreach ($skills as $key => $value) {
            if ($value['rule'] == 'and') {
                $users->where(function ($query) use ($value) {
                    $query->whereIn(
                        'id',
                        PersonalSkillset::where('common_skill_id', $value['skill'])
                    ->where('level', '=', $value['lvl'])
                    ->pluck('staff_id')
                    );
                });
            }

            if ($value['rule'] == 'or') {
                $users->whereOr(function ($query) use ($value) {
                    $query->whereIn(
                        'id',
                        PersonalSkillset::where('common_skill_id', $value['skill'])
                    ->where('level', '=', $value['lvl'])
                    ->pluck('staff_id')
                    );
                });
            }
        }

        $usr = $users->get(['id','persno']);

        $content = [];


        $result = "";
        //  dd($users->get());
        foreach ($users->get() as $key => $value) {

            $isi = ['user_id'=> $value->id,'persno'=>$value->persno, 
            'skills'=>$value->getPersonalSkillset()
        ];
            array_push($content, $isi);
        }
        
        $result= ["users"=>$content];
  
    
        return $content;
    }
}
