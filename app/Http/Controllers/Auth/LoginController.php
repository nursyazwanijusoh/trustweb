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
            'staffid' => 'required', 'password' => 'required',
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

      // find from staff table
      $staffdata = staff::where('staff_no', $ldapstaffid)->first();
      if($staffdata){
        // get the user table
        // $userdata = User::find($staffdata->)


      } else {
        // new data. create it

      }




      Auth::loginUsingId(0, $req->filled('remember'));

      if(Auth::check()){
        return $this->sendLoginResponse($req);
      } else {
        // return "lala";
        return $this->sendFailedLoginResponse($req);
      }
      // redirect(route('pg'));
      // Auth::loginUsingId(1);
      // return redirect()->intended('pg');
      // return route('api.home');

    }

}
