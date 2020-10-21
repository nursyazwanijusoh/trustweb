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
        $pss = PersonalSkillset::query();

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


  


        //dd($value['skill']);
     
        //$users->whereIn('id', PersonalSkillset::where('common_skill_id', $value)->where('level', '!=', 0)->pluck('staff_id'));

        $result = $users->get(['id']);
        //dd($users->toSql());
    
        return $result;
    }
}
