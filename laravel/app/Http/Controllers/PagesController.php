<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home(){
		return view('home');
	}
	
	public function login(){
		return view('login');
	}
	
	public function search(){
		return view('search');
	}
	
	
	public function tryLogin(){
		return view('tryLogin');
	}
	
	
	
	
}
