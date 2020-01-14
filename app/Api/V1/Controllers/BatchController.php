<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SapEmpProfile;
use App\SapLeaveInfo;

class BatchController extends Controller
{
	public function loadEmplProfile(){
		// just in case it takes too long
		set_time_limit(0);

		$toprocess = SapEmpProfile::where('load_status', 'N')->get();

		return $toprocess->count();

	}

	public function loadEmplLeave(){
		// just in case it takes too long
		set_time_limit(0);

		$toprocess = SapLeaveInfo::where('load_status', 'N')->get();
		foreach($toprocess as $cuti){

		}

		return $toprocess->count();

	}

}
