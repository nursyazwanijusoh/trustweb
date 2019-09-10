<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Feedback;
use App\ActivityType;
use App\TaskCategory;
use App\common\GDWActions;

class MiscController extends Controller
{


	function sendFeedback(Request $req){
		$input = app('request')->all();

		$rules = [
			'title' => ['required'],
			'content' => ['required'],
			'device' => ['required']
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$fb = new Feedback;
		$fb->staff_id = $req->filled('staff_id') ? $req->staff_id : 0;
    $fb->title = $req->title;
    $fb->content = $req->content;
		$fb->agent = $req->device;
		$fb->contact = $req->filled('ctc') ? $req->ctc : '';
		$fb->status = 1;
		$fb->save();

		return $this->respond_json(200, 'Success', $fb);
	}

	function GwdAddActivity(Request $req){
		$input = app('request')->all();

		$rules = [
			'title' => ['required'],
			'staff_id' => ['required'],
			'hours' => ['required'],
			'acttype' => ['required'],
			'actcat' => ['required'],
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$act = GDWActions::addActivity($req, $req->staff_id);
		return $this->respond_json(200, 'Success', $act);
	}

	function GwdEditActivity(Request $req){
		$input = app('request')->all();

		$rules = [
			'title' => ['required'],
			'id' => ['required'],
			'hours' => ['required'],
			'acttype' => ['required'],
			'actcat' => ['required'],
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$act = GDWActions::editActivity($req, $req->staff_id);
		if($act == '404'){
			return $this->respond_json(404, 'gwd activity not found', $input);
		}
		return $this->respond_json(200, 'Success', $act);
	}

	function GwdDelActivity(Request $req){
		$input = app('request')->all();

		$rules = [
			'id' => ['required'],
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$act = GDWActions::deleteActivity($req->id);
		if($act == '404'){
			return $this->respond_json(404, 'gwd activity not found', $input);
		}
		return $this->respond_json(200, 'Deleted', []);

	}

	function GwdGetSummary(Request $req){
		$input = app('request')->all();

		$rules = [
			'staff_id' => ['required'],
			'date' => ['required']
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$redata = GDWActions::getActSummary($req->staff_id, $req->date);

		return $this->respond_json(200, 'Success', $redata);
	}

	function GwdGetActivities(Request $req){
		$input = app('request')->all();

		$rules = [
			'staff_id' => ['required'],
			'date' => ['required']
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$redata = GDWActions::getGwdActivities($req->staff_id, $req->date);

		return $this->respond_json(200, 'Success', $redata);
	}

	function GwdGetActType(){
		$redata = ActivityType::where('status', 1)->get();
		foreach ($redata as $key => $value) {
			$value->value = $value->descr;
		}
		return $this->respond_json(200, 'Success', $redata);
	}

	function GwdGetActCat(){
		$redata = TaskCategory::where('status', 1)->get();
		foreach ($redata as $key => $value) {
			$value->value = $value->descr;
		}
		return $this->respond_json(200, 'Success', $redata);
	}

}
