<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SapEmpProfile;
use App\SapLeaveInfo;
use App\BatchJob;
use App\common\BatchHelper;
use App\Jobs\CreateDailyPerformance;

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



	public function GwdCreateDayPerf(Request $req){
		if($req->filled('date')){
			$ddate = $req->date;
		} else {
			$ddate = date('Y-m-d');
		}

		$curjob = BatchJob::where('job_type', 'Create daily performance')
			->whereDate('from_date', $ddate)
			->whereIn('status', ['New', 'Processing', 'Completed'])
			->first();

		if($curjob){
			// already got the job
			return $this->respond_json(200, 'Job already exist', []);
		} else {
			// CreateDailyPerformance::dispatch($ddate);
		}

		return $this->respond_json(200, 'Job Scheduled', []);

	}

}
