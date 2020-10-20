<?php

namespace App\Api\V1\Controllers\Tribe;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\CommonSkillset;


class SkillController extends Controller
{
   public function getSkills(Request $req){

      $skillset = CommonSkillset::all('id','name');
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







}
