<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SapEmpProfile;
use App\SapLeaveInfo;
use App\common\BatchHelper;

class BatchController extends Controller
{
	public function loadEmplProfile(){
		// just in case it takes too long
		set_time_limit(0);

		BatchHelper::loadOMData();

		return "completed";

	}

	public function loadEmplLeave(){
		// just in case it takes too long
		set_time_limit(0);

		BatchHelper::loadCutiData();

		return "completed";

	}

}
