<?php

namespace MovieBuffs\Http\Controllers\Auth;

use MovieBuffs\User;
use MovieBuffs\Profile;
use MovieBuffs\UserDetail;
use MovieBuffs\UserPreference;
use MovieBuffs\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = 'preferences';

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
		$rules = array('name' => 'bail|required|string|max:20',
            'email' => 'bail|required|string|email|max:50|unique:users',
            'password' => 'bail|required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed');
			
		$messages = array(
						'name.required'=>'You must give yourself a name.',
						'name.string'=>'Names must be strings.',
						'name.max'=>'Your name cannot be larger than 50 characters',
						'email.required'=>'You must enter an email address.',
						'email.string'=>'The email address must be a string.',
						'email.email'=>'The email must be a valid email',
						'email.max'=>'The email cannot be longer than 50 characters.',
						'email.unique'=>'That email is already being used.',
						'password.required'=>'You need a password to log in with.',
						'password.regex'=>"The password must have at least 1 uppercase letter, 1 lowercase letter, and 1 number.",
						'password.min'=>'Your password must be at least 6 characters long.',
						'password.confirmed'=>'Your confirmed password must match your password.'
						);
		
		$errors = Validator::make($data, $rules, $messages);
		

		
        return $errors;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \MovieBuffs\User
     */
    protected function create(array $data)
    {
		
		$newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
			'preferenceSet'=>0
        ]);
		
		
		$id = $newUser->id;
		
		$userDetail = new UserDetail;
		$userDetail->id = $id;
		$userDetail->location = " ";
		$userDetail->movies = " ";
		$userDetail->genreAction = 0;
		$userDetail->genreMystery = 0;
		$userDetail->genreHorror = 0;
		
		$userDetail->save();
		
		
		
		$userPreference = new UserPreference;
		$userPreference->id = $id;
		$userPreference->location = 0;
		$userPreference->movies = 0;
		$userPreference->genre = 0;
		
		$userPreference->save();
		
		
		$profile = new Profile;
		$profile->id = $id;
		$profile->save();
		
		return $newUser;
    }
}
