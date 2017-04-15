<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App;


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

    use AuthenticatesUsers {
		logout as performLogout;
	}

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
		$this->redirectTo = route('dashboard.home', App::getLocale());
        $this->middleware('guest', ['except' => 'logout']);
    }
	
	/**
     * Redirect the user to his own language after logging in. Ignores the protected $redirectTo and $this->redirectTo properties
     *
     * @return void
     */
	public function redirectTo() {
		return route('dashboard.home', App::getLocale());
	}
	
	/**
     * Redirect the user to his own language after logging out. Ignores the Laravel default settings.
     *
     * @return void
     */
	public function logout(Request $request)
	{
		$this->performLogout($request);
		return redirect(route('page.index', App::getLocale()));
	}
}
