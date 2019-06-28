<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Feedback;

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
		$fb->status = 1;
		$fb->save();

		return $this->respond_json(200, 'Success', $fb);
	}

}
