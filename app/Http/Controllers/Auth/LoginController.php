<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        if(is_numeric($request->get('login'))){
            return ['phone'=>$request->get('login'),'password'=>$request->get('password')];
        }
        elseif (filter_var($request->get('login'))) {
            return ['email' => $request->get('login'), 'password'=>$request->get('password')];
        }
        return $request->only($this->username(), 'password');
    }

    public function username()
    {
        return 'login';

//        $login = request()->input('login');
//
//        if(is_numeric($login)){
//            $field = 'login';
//        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
//            $field = 'login';
//        }else{
//            $field = 'login';
//        }
//
//        request()->merge([$field => $login]);
//
//        return $field;
    }
}
