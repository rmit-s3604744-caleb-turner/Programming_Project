<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home(){
		return view('home');
	}
}
