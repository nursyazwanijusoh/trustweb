<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SkillCategory;
use App\SkillType;
use App\CommonSkillset;


class SkillsetController extends Controller
{

	public function SSGetCat(Request $req){

		

		return $this->respond_json(200, 'Job Scheduled', []);

	}

}
