<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\User;


// Controller that handles the user's dashboard.
class DashboardController extends Controller
{
    // Constructor Class
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Shows the application's dashboard.
    public function index()
    {
		// Get the user's ID and return the dashboard with any posts they've made.
		$user_id = auth()->user()->id;
		$user = User::find($user_id);
		
        return view('dashboard')->with('posts', $user->posts);
    }
}
