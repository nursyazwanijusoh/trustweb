<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Partner;
use App\common\UserRegisterHandler;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/postreg';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'staff_no' => ['required', 'string', 'max:10', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'partner_id' => ['required', 'integer'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = UserRegisterHandler::register($data);

        return $user;
    }

    public function showRegistrationForm()
{
    $partn=Partner::all();
    return view('auth.register', compact('partn'));
}

public function register(Request $request)
  {
      $this->validator($request->all())->validate();

      event(new Registered($user = $this->create($request->all())));

      // $this->guard()->login($user);

      return $this->registered($request, $user)
                      ?: redirect(route('postreg', ['id' => $user->id], false));
  }

}
