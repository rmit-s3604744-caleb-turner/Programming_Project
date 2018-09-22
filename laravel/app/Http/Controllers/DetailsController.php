<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\User;
use MovieBuffs\UserDetail;
use MovieBuffs\UserPreference;
use DB; 
use Illuminate\Support\Facades\Auth;



// Controller that is responsible for showing and changing the logged in user's details.
class DetailsController extends Controller
{


    // Shows the user's preferences.
    public function index()
    {
		// If the user is not logged in, redirect them.
		if(! Auth::check()){
			return redirect('/')->with('error', 'Unauthorised Page: Access Denied');
		}

		// Get the user's details and preferences.
        $userID = auth()->user()->id;
		$userDetails = UserDetail::find($userID);
		$userPreferences = UserPreference::find($userID);
		
		// Put them into an array.
		$details = [$userID, $userDetails, $userPreferences];

		// Display the user's preferences page.
		return view('preferences')->with('details', $details);
    }

	
	// Updates the user's  preferences.
    public function update(Request $request, $id)
    {
		
		// Validation for the submitted preference change.
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

		// Find the user's details within the database.
		$userDetail = UserDetail::find($id);
		
		// Set the user's details to the requested details.
		$userDetail->location = $request->input('location');
		$userDetail->movies = $request->input('movie');
		$userDetail->genreAction = $request->input('genreAction');
		$userDetail->genreMystery = $request->input('genreMystery');
		$userDetail->genreHorror = $request->input('genreHorror');
		
		// Save the details.
		$userDetail->save();
		
		// Find the user's preferences within the database.
		$userPreference = UserPreference::find($id);
		
		// Set the user's preferences to the requested preferences.
		$userPreference->location = $request->input('locationImp');
		$userPreference->movies = $request->input('movieImp');
		$userPreference->genre = $request->input('genreImp');
		
		// Save the changes.
		$userPreference->save();
		
		// Find the user in the database.
		$user = User::find($id);
		// Show that their preferences are set (so that they can be included in matchmaking).
		$user->preferenceSet = 1;
		// Save.
		$user->save();
		
		// Redirect the user to the homepage.
		return redirect('/')->with('success', 'Details Updated');
    
    }
}
