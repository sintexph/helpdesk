<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Factory;
use App\User;
use App\Helpers\UserRole;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Override the login function to allow the program can do multiple login details [Username, Id number and email]
     */
    public function login(Request $request)
    {
        $this->validate($request,[
            'username'=>'string',
            'password' => 'required|string',
        ]);
        

        $user=\App\User::where(function($condition)use($request){
            $condition->orWhere('email',$request['username'])
            ->orWhere('username',$request['username'])
            ->orWhere('id_number',$request['username']);
        })->first();

        if($user==null)
            return response()->json(['errors'=>['username'=>[trans('auth.failed')]]],422);
        
        $request['username']=$user->username;
        

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        return "username";
    }

    public function showLoginForm()
    {
        $factories=Factory::all();
        $supports=User::where('role','<>',UserRole::SENDER)->get();
        return view('auth.login',['factories'=>$factories,'supports'=>$supports]);
    }
    
}
