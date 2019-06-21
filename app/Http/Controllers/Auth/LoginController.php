<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\common\UserRegisterHandler;

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

      $logresp = UserRegisterHandler::userLogin($req->staff_id, $req->password, 1);

      if($logresp['msg'] == 'failed'){
        return view('auth.login', ['loginerror' => 'Invalid Credential', 'type' => 'warning']);
      } elseif ($logresp['msg'] == 'email') {
        return view('auth.verify', ['staff' => $logresp['user']->id]);
      } elseif ($logresp['msg'] == 'pending') {
        return view('auth.approve');
      }

      session(['staffdata' => $logresp['user']]);

      return redirect()->intended(route('staff'));

    }


}
