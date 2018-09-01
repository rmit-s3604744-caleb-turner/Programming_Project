<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user_id = auth()->user()->id;
		$user = User::find($user_id);
		
        return view('dashboard')->with('posts', $user->posts);
    }
}
