<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

	/**
	*	authenticate the provided credential with ldap
	*/
	function doLogin(Request $req){

		set_error_handler(array($this, 'errorHandler'));
		$errorcode = 200;

		// first, validate the input
		$input = app('request')->all();
		$rules = [
			'STAFF_ID' => ['required'],
			'PASSWORD' => ['required']
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$username = $req->STAFF_ID;
		$password = $req->PASSWORD;

		$ldapherpel = new LdapHelper;

		return $ldapherpel->doLogin($username, $password);

	}

	// to be called by API
	function getUserInfo(Request $req){
		// first, validate the input
		$input = app('request')->all();
		$rules = [
			'key' => ['required'],
			'type' => ['required']
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		return $this->fetchUser($req->key, $req->type);
	}
}
