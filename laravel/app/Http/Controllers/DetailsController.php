<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\User;
use MovieBuffs\UserDetail;
use MovieBuffs\UserPreference;
use DB; 
use Illuminate\Support\Facades\Auth;

class DetailsController extends Controller
{


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(! Auth::check()){
			return redirect('/')->with('error', 'Unauthorised Page: Access Denied');
		}
		
		// get id
        $userID = auth()->user()->id;
		
	
		
		
		$userDetails = UserDetail::find($userID);
		
		
		
		$userPreferences = UserPreference::find($userID);
		
		$details = [$userID, $userDetails, $userPreferences];
		
		
		
		return view('preferences')->with('details', $details);
		
		
		
		//$users = User::where('id','!=', auth()->user()->id)->get();
		
		//$user_id = auth()->user()->id;
			
	    //return $users;
    }

	
    public function update(Request $request, $id)
    {
		
        $this->validate($request, [
			'location'=>'required',
			'movie'=>'required',
			'genreAction'=>'required',
			'genreMystery'=>'required',
			'genreHorror'=>'required',
			'locationImp'=>'required',
			'movieImp'=>'required',
			'genreImp'=>'required'
		]);
		

		
		
		$userDetail = UserDetail::find($id);
		$userDetail->location = $request->input('location');
		$userDetail->movies = $request->input('movie');
		$userDetail->genreAction = $request->input('genreAction');
		$userDetail->genreMystery = $request->input('genreMystery');
		$userDetail->genreHorror = $request->input('genreHorror');
		
		$userDetail->save();
		
		
		
		$userPreference = UserPreference::find($id);
		$userPreference->location = $request->input('locationImp');
		$userPreference->movies = $request->input('movieImp');
		$userPreference->genre = $request->input('genreImp');
		
		$userPreference->save();
		
		return redirect('/')->with('success', 'Details Updated');
    
    }


	
}
