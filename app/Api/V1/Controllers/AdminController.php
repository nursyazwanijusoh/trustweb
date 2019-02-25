<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\User;

class AdminController extends Controller
{
    function adminAddStaff(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_no' => ['required'],
        'lob' => ['required'],
        'allowed_building' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $staff = User::where('staff_no', $req->staff_no)->first();
      if($staff){
        // staff exist
      } else {
        // new staff. create
        $staff = new User;
        $staff->staff_no = $req->staff_no;
        $staff->name = ' ';
        $staff->email = ' ';
        $staff->role = 3; // default to staff
      }

      $staff->lob = $req->lob;
      $staff->allowed_building = $req->allowed_building;
      $staff->status = 1; // set to active
      $staff->save();

      return $this->respond_json(200, 'Staff updated', $staff);

    }

    function AdminUpdateStaff(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'staff_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $staff = User::where('id', $req->staff_id)->first();
      if($staff){
        if($req->filled('lob')){
          $staff->lob = $req->lob;
        }

        if($req->filled('allowed_building')){
          $staff->allowed_building = $req->allowed_building;
        }

        if($req->filled('status')){
          $staff->status = $req->status;
        }

        if($req->filled('role')){
          $staff->role = $req->role;
        }

        $staff->save();

        return $this->respond_json(200, 'staff updated', $staff);
      } else {
        return $this->respond_json(404, 'staff not found', $input);
      }

    }
}
