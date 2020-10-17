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

      $cfgs = CommonSkillset::all();
      return $cfgs;
    }







}
