<?php

namespace MovieBuffs\Http\Controllers\Auth;

use MovieBuffs\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use MovieBuffs\User;
use DB;

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

	
	protected function authenticated($request, $user){
		$id = $user->id;
		
		
		// if not set preferences
		if($user->preferenceSet == 0){
			return redirect('preferences')->with('success', 'Please submit your preferences.');
		}
		
		return redirect('dashboard');
		
	}
	
	
	
	
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
	 
    //protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
