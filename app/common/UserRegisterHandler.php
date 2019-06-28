<?php

namespace App\common;

use App\User;
use App\Subordinate;
use App\VerifyUser;
use App\Mail\VerifyMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Api\V1\Controllers\LdapHelper;

class UserRegisterHandler
{
  public static function register(array $data){
    $user = new User;
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->staff_no = $data['staff_no'];
    $user->password = Hash::make($data['password']);
    $user->verified = false;
    $user->isvendor = 1;
    $user->role = 3;
    $user->status = 2;
    $user->partner_id = $data['partner_id'];
    $user->save();

    // increment the user count
    $pner = $user->Partner;
    $pner->increment('staff_count');
    $pner->save();

    $verify = new VerifyUser;
    $verify->user_id = $user->id;
    $verify->token = str_random(35);
    $verify->save();

    \Mail::to($user->email)->send(new VerifyMail($user));

    return $user;
  }

  public static function userLogin($username, $password, $isweb = 0){
    // first, check if this user exists
    $ldapherpel = new LdapHelper;
    $errormsg = "success";
    $field = 'staff_no';

    if(strpos($username, '@') !== false){
      $field = 'email';
    }

    $user = User::where($field, $username)->first();

    if($user && $user->isvendor == 1){
      // is vendor. do normal login

      if($user->verified == 0){
        $errormsg = "email";
      } elseif($user->status == 1){
        if(Auth::attempt([
          'email' => $username,
          'password' => $password
        ])){
          $user->status = 1;
          $user->save();
        } else {
          $errormsg = "failed";
        }
      } else{
        $errormsg = "pending";
      }


    } else {

      if($field == 'email'){
        $username = $user->staff_no;
      }

      // user not exist or is TM staff. Try login through LDAP
		  $ldapresp = $ldapherpel->doLogin($username, $password);
      if($ldapresp['code'] != 200){
        // bad ldap login
        $errormsg = "failed";
  		} else {
        // update the data back to the User
        $user = UserRegisterHandler::updateFromLdap($ldapresp);

        // get the subords info
        $newsubs = $ldapherpel->getSubordinate($user->name);
        if($newsubs['code'] == 200){
          UserRegisterHandler::getSubords($user->id, $newsubs['data']);
        }

        UserRegisterHandler::amIsubords($user->id, $user->staff_no);

        if($isweb == 1){
          Auth::loginUsingId($user->id, false);
        }
      }
    }

    return [
      'user' => $user,
      'msg' => $errormsg
    ];
  }

  private static function updateFromLdap($ldapresp){
    // get the username
		$ldapstaffid = $ldapresp['data']['STAFF_NO'];

		// find from User table
		$staffdata = User::where('staff_no', $ldapstaffid)->first();

		if($staffdata){
		} else {
			// new data. create it
			$staffdata = new User;
			$staffdata->staff_no = $ldapstaffid;
			$staffdata->status = 1; // set it to inactive
			$staffdata->role = 3;
		}

		$tmobile = $ldapresp['data']['MOBILE_NO'];
		if(substr($tmobile, 0, 1) === '0'){
			$tmobile = '6' . $tmobile;
		}

		// overwrite with ldap data
		$staffdata->email = $ldapresp['data']['EMAIL'];
		$staffdata->mobile_no = $tmobile;
		$staffdata->name = $ldapresp['data']['NAME'];
		$staffdata->lob = $ldapresp['data']['DEPARTMENT'];
		$staffdata->unit = $ldapresp['data']['UNIT'];
		$staffdata->subunit = $ldapresp['data']['SUBUNIT'];
		$staffdata->save();

    return $staffdata;
  }

  private static function getSubords($staff_id, $newsubs){
    $cursubs = Subordinate::where('superior_id', $staff_id)->get();

    // first, remove non subs
    foreach($cursubs as $asub){
      $subfound = false;
      foreach($newsubs as $nsub){
        if($nsub['staff_no'] == $asub->staff_no){
          $subfound = true;
        }
      }

      if($subfound == false){
        $asub->delete();
      }

    }

    // then add the new subs
    foreach($newsubs as $nsub){
      $subfound = false;
      foreach ($cursubs as $asub) {
        if($nsub['staff_no'] == $asub->staff_no){
          $subfound = true;
        }
      }

      if($subfound == false){
        $anusib = new Subordinate;
        $anusib->superior_id = $staff_id;
        $anusib->sub_name = $nsub['staff_name'];
        $anusib->sub_staff_no = $nsub['staff_no'];

        // check if this is a registered user
        $subuser = User::where('staff_no', $nsub['staff_no'])->first();
        if($subuser){
          $anusib->subordinate_id = $subuser->id;
        }

        $anusib->save();
      }

    }

  }

  private static function amIsubords($staff_id, $staff_no){
    // check if i'm someone's subordinates. update the id
    $cursubs = Subordinate::where('sub_staff_no', $staff_no)->get();
    foreach($cursubs as $asub){
      $asub->subordinate_id = $staff_id;
      $asub->save();
    }
  }

  private static function inactivateUser($staff_id){
    $user = User::find($staff_id);
    $user->status = 0;
    $user->save();

    return $user;

  }

}
