<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Api\V1\Controllers\LdapHelper;

use App\User;
use App\Subordinate;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    // overwrite the trait controller
    public function login(Request $req)
    {
      $this->validate($req, [
            'staff_id' => 'required', 'password' => 'required',
      ]);

      // first authenticate using ldap
      $ldapherpel = new LdapHelper;
  		$resp = $ldapherpel->doLogin($req->staff_id, $req->password);

      if($resp['code'] != 200){
        // bad login
        // return $resp;
        return view('auth.login', ['loginerror' => $resp['msg']]);
      }

      // get the username
      $ldapstaffid = $resp['data']['STAFF_NO'];

      // find from User table
      $staffdata = User::where('staff_no', $ldapstaffid)->first();

      if($staffdata){
      } else {
        // new data. create it
        $staffdata = new User;
        $staffdata->staff_no = $ldapstaffid;
        $staffdata->status = 0; // set it to inactive
  			$staffdata->role = 3;
      }

      // overwrite with ldap data
      $staffdata->email = $resp['data']['EMAIL'];
      $staffdata->mobile_no = $resp['data']['MOBILE_NO'];
      $staffdata->name = $resp['data']['NAME'];
      $staffdata->lob = $resp['data']['DEPARTMENT'];
      $staffdata->unit = $resp['data']['UNIT'];
  		$staffdata->subunit = $resp['data']['SUBUNIT'];
      $staffdata->save();

      // $newsubs = $ldapherpel->getSubordinate($staffdata->name);
      // if($newsubs['code'] == 200){
      //   $this->getSubords($staffdata->id, $newsubs['data']);
      // }
      //
      // $this->amIsubords($staffdata->id, $ldapstaffid);

      // then 'auth' it
      Auth::loginUsingId($staffdata->id, $req->filled('remember'));

      session(['staffdata' => $staffdata]);

      return redirect()->intended(route('staff'));

    }

    private function getSubords($staff_id, $newsubs){
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

    private function amIsubords($staff_id, $staff_no){
      // check if i'm someone's subordinates. update the id
      $cursubs = Subordinate::where('sub_staff_no', $staff_no)->get();
      foreach($cursubs as $asub){
        $asub->subordinate_id = $staff_id;
        $asub->save();
      }
    }
}
