<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{

	/**
	*	authenticate the provided credential with ldap
	*/
	function doLogin(Request $req){

		set_error_handler(array($this, 'errorHandler'));

		$input = app('request')->all();

		$rules = [
			'staff_id' => ['required'],
			'password' => ['required']
		];

		$validator = app('validator')->make($input, $rules);
		if($validator->fails()){
			return $this->respond_json(412, 'Invalid input', $input);
		}

		$username = $req->staff_id;
		$password = $req->password;

		$ldapherpel = new LdapHelper;
		$ldapresp = $ldapherpel->doLogin($username, $password);

		if($ldapresp['code'] != 200){
			// bad login
			return $ldapresp;
		}

		// get the username
		$ldapstaffid = $ldapresp['data']['STAFF_ID'];

		// find from User table
		$staffdata = User::where('staff_id', $ldapstaffid)->first();

		if($staffdata){
		} else {
			// new data. create it
			$staffdata = new User;
			$staffdata->staff_id = $ldapstaffid;
		}

		// overwrite with ldap data
		$staffdata->email = $ldapresp['data']['EMAIL'];
		$staffdata->mobile_no = $ldapresp['data']['MOBILE_NO'];
		$staffdata->name = $ldapresp['data']['NAME'];
		$staffdata->save();

		$respon = [
			'ldap' => $ldapresp['data'],
			'user' => $staffdata
		];

		return $this->respond_json(200, 'OK', $respon);

	}

	// to be called by API
	function getUserInfo(Request $req){
		// first, validate the input
		$rules = [
			'key' => ['required'],
			'type' => ['required']
		];

		$val = $this->validate($rules);

		if($val['code'] != 200){
			return $val;
		}

		$ldapherpel = new LdapHelper;
		return $ldapherpel->fetchUser($req->key, $req->type);
	}
}
