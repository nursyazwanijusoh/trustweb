<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Api\V1\Controllers\LdapHelper;

use App\User;

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
  		$resp = $ldapherpel->doLogin($req->staffid, $req->password);

      if($resp['code'] != 200){
        // bad login
        return view('auth.login', ['loginerror' => 'bad credential']);
      }

      // get the username
      $ldapstaffid = $resp['data']['STAFF_ID'];

      // find from User table
      $staffdata = User::where('staff_id', $ldapstaffid)->first();

      if($staffdata){
      } else {
        // new data. create it
        $staffdata = new User;
        $staffdata->staff_id = $ldapstaffid;
      }

      // overwrite with ldap data
      $staffdata->email = $resp['data']['EMAIL'];
      $staffdata->mobile_no = $resp['data']['MOBILE_NO'];
      $staffdata->name = $resp['data']['NAME'];
      $staffdata->save();

      // then 'auth' it
      Auth::loginUsingId($staffdata->id, $req->filled('remember'));

      return redirect()->intended('home');

    }

}
