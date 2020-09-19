<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\common\TeamHelper;
use \Carbon\Carbon;

class TeamController extends Controller
{
	public function GetTeamAvgPerf(Request $req){
		$input = app('request')->all();

		$rules = [
			'staff_id' => ['required'],
			'start_date' => ['required'],
			'end_date' => ['required']
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$user = User::find($req->staff_id);

		if($user){

			$cdate = new Carbon($req->start_date);
			$ldate = new Carbon($req->end_date);

			if($cdate->gt($ldate)){
				return $this->respond_json(500, 'Start date is after end date', $input);
			}

			if($cdate->diffInDays($ldate) > 7){
				return $this->respond_json(500, 'Date range is greater than 7 days', $input);
			}

			$retval = TeamHelper::GetTeamPerfInfo($user, $cdate, $ldate);
			unset($retval['daterange']);
			return $this->respond_json(200, 'Team Perf Summary', $retval);
		} else {
			return $this->respond_json(404, 'User 404', $input);
		}


	}

}
