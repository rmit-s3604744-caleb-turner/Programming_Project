<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\User;
use MovieBuffs\UserDetail;
use MovieBuffs\UserPreference;
use DB;
use Illuminate\Support\Facades\Auth;
use Hootlex\Friendships\Traits\Friendable;
use MovieBuffs\Profile;
use willvincent\Rateable\Rateable;


// Controller that handles matches.
class MatchController extends Controller
{
	
	// Helper functions.
	private function getID(){
		return auth()->user()->id;
	}
	
	.
	private function getDetails($id){
		return UserDetail::find($id);
	}
	
	
	private function getPreferences($id){
		return UserPreference::find($id);
	}
	
	
	// Gets every user's ID except the one that is currently logged in.
	private function getOthers(){
		$userID = self::getID();
		// Only getting users who have set their preferences
		$otherUsers = User::where('id', '!=', $userID)->where('preferenceSet', '1')->pluck('id')->toArray();
		
		return $otherUsers;
	}
	
	
	private function getName($id){
		return User::where('id', $id)->get()->pluck('name');
	}
	
	
	private function getRating($id){
		$profile = Profile::find($id);
		$reviewCheck = DB::table("ratings")->where("rateable_id", $id)->get();
		if(sizeof($reviewCheck) == 0){
			$rating = '0';
		}else{
			$rating = $profile->averageRating;
		}
		
		return $rating;
	}
	
	
	private function isSimilarText($text1, $text2){
		$text1 = strtolower($text);
		$text2 = strtolower($text2);
		
		if(strcmp($text1, $text2) == 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	
	// Bubblesorting function that gets called back.
	private function callbackSort($a, $b){
		return ($a[3] >= $b[3]) ? -1 : 1;
	}
	// End of helper functions.
	
	
	// Display function for when the user has not refined their search.
	public function index(){
		return self::getMatchlist(0);
    }	
	
	// Display function for when the user has refined their search.
	public function refine(Request $request){
		$threshold = $request->threshold;
		return self::getMatchlist($threshold);
	}
	

	// Get the list of potential matches.
	private function getMatchlist($threshold){
		
		// Make sure the user is logged in.
		if(! Auth::check()){
			return redirect('/')->with('error', 'Unauthorised Page: Access Denied');
		}
		
		$userID = self::getID();
		
		$user = User::find($userID);
		
		// Make sure the user has set their preferences so we can calculate matches.
		if($user->preferenceSet==0){
			return redirect('preferences')->with('error', "You can't find matches until you have set your preferences.");
		}
		
		// Get the potential matches.
		$array = self::getMatchArray($threshold);
		
		// Return the page with a list of matches.
		return view('matchlist')->with('array', $array);
	}
	
	
	// Function that returns an array of users sorted by their similarity percentage.
	private function getMatchArray($threshold){

		// Get the user's match preferences.
		$user1ID = self::getID();
		$user1Preferences = self::getPreferences($user1ID);
		
		// Find the highest possible score so that matches are displayed as percentages.
		$maxScore = ($user1Preferences->location + $user1Preferences->movies + $user1Preferences->genre);
		$scores = array();
		
		$userList = self::getOthers();
		
		// Get the set of already matched users (so they are not included in matchmaking).
		$self = User::find($user1ID);
		$friends = $self->getFriends();
		
		
		// For each user...
		foreach($userList as $user2ID){
			
			// That is not already matched...
			if(! $friends->contains('id', $user2ID)){
				
				// Get the user's rating.
				$rating = self::getRating($user2ID);
		
				// If they meet the threshold...
				if($rating >= $threshold){
					
					// Get their similarity to the logged in user.
					$similarity = self::findSimilarity($user1ID, $user2ID);
					
					// Turn the similarity into a percentage (for display purposes)
					$percentage = $similarity / $maxScore;
					$percentage = round((float)$percentage * 100) . '%';
					
					// Get their name (for display purposes).
					$user2Name = self::getName($user2ID);
						
					// Add them to the array.
					$tempArray = array();
					array_push($tempArray, $user2Name, $percentage, $user2ID, $similarity, $rating);
					array_push($scores, $tempArray);
				}
				
			}
			
		}

		// Sort the array of matches.
		usort($scores, "self::callbackSort");
			
		// Return the array of matches.
		return $scores;
			
	}

	
	// Function that finds the similarity of 2 users.
	private function findSimilarity($userID, $user2ID){
	
		// Get the details of the users.
		$user1Details = self::getDetails(self::getID());
		$user2Details = self::getDetails($user2ID);
		

		// Find the similarity of the 3 elements.
		$genreSimilarity = self::genreSimilarity($user1Details, $user2Details);
		$movieSimilarity = self::movieSimilarity($user1Details, $user2Details);
		$locationSimilarity = self::locationSimilarity($user1Details, $user2Details);
		
		// Get the preferences to weigh by.
		$user1Preferences = self::getPreferences(self::getID());
		
		// Weigh the similarity.
		$genrePreference = $user1Preferences->genre;
		$moviePreference = $user1Preferences->movies;
		$locationPreference = $user1Preferences->location;
		
		$genreSimilarity *= $genrePreference;
		$movieSimilarity *= $moviePreference;
		$locationSimilarity *= $locationPreference;
		
		// Return the similarity score.
		$score = $genreSimilarity + $movieSimilarity + $locationSimilarity;
		return $score;
	}

	
	// Function that finds the similarity of the users' taste in genres.
	private function genreSimilarity($user1Details, $user2Details){
		
		$similarity = 0;
		$similarity += self::findSpecificGenreSimilarity('genreAction', $user1Details, $user2Details);
		$similarity += self::findSpecificGenreSimilarity('genreMystery', $user1Details, $user2Details);
		$similarity += self::findSpecificGenreSimilarity('genreHorror', $user1Details, $user2Details);
		$similarity /= 3;
		
		return $similarity;
	}
	
	
	// Function that finds the similarity of 2 users in a given genre.
	private function findSpecificGenreSimilarity($genre, $user1Details, $user2Details){
		
		$similarity = 0;
		$user1Like = $user1Details->$genre;
		$user2Like = $user2Details->$genre;

		// Finding the cosine similarity.
		$difference = abs($user1Like - $user2Like);
		$difference *= 0.2;
		$difference = 1-$difference;
			
		$similarity+= $difference;
		
		return $similarity;
		
	}
	
	
	// Function that finds the similarity of 2 users' favourite movie.
	private function movieSimilarity($user1Details, $user2Details){
	
		$user1Movie = $user1Details->movies;
		$user2Movie = $user2Details->movies;
		
		return self::isSimilarText($user1Movie, $user2Movie);
	
	}
	
	
	// Function that finds the similarity of the 2 users' location.
	private function locationSimilarity($user1Details, $user2Details){
		$user1Location = $user1Details->location;
		$user2Location = $user2Details->location;
		
		return self::isSimilarText($user1Location, $user2Location);

	}
	

	
	
	
	// Functions to handle matching users.
	public function sendFriendRequest(Request $request){
		$user = User::find(self::getID());
		$user2 = User::find($request->id);
		$user->befriend($user2);
		
		return redirect('matchlist')->with('success', 'Match request sent.');
	}
	
	public function acceptFriendRequest(Request $request){
		$user = User::find(self::getId());
		$user->acceptFriendRequest(User::find($request->id));
		
		return redirect('matches')->with('success', 'Match request accepted.');
	}
	
	public function denyFriendRequest(Request $request){
		$user = User::find(self::getId());
		$user->denyFriendRequest(User::find($request->id));
		
		return redirect('matches')->with('error', 'Match request denied.');
	}
	
	
	
	
	
	// Function that displays the user's accepted / pending matches.
	public function matches(){
		
		$user = User::find(self::getID());
		
		$user1Preferences = self::getPreferences($user->id);
		$maxScore = ($user1Preferences->location + $user1Preferences->movies + $user1Preferences->genre);

		$pending = $user->getFriendRequests();
		$requestArray = array();
		
		// For each pending request I am involved in...
		foreach($pending as $request){
			
			// If I did not send the request...
			if($pending[0]->sender_id != self::getID()){
				
				// Find the similarity by my preferences (for display purposes)
				$user2 = User::find($pending[0]->sender_id);
				$similarity = self::findSimilarity($user->id, $user2->id);
			
				if(($similarity == 0) || ($maxScore == 0)){
					$percentage = 0 . '%';
				}else{
					$percentage = $similarity / $maxScore;
					$percentage = round((float)$percentage * 100) . '%';
				}

				$tempArray = array();
				array_push($tempArray, $user2->name, $percentage, $user2->id);
				array_push($requestArray, $tempArray);
					
			}
		}
		
		
		$accepted = $user->getAcceptedFriendships();
		$matches = array();
		// For each accepted request...
		foreach($accepted as $match){
			
			// Get the other person's details.
			if($match->sender_id == $user->id){
				$user2 = User::find($match->recipient_id);
			}else{
				$user2 = User::find($match->sender_id);
			}

			$tempArray = array();
			array_push($tempArray, $user2->name, $user2->id);
			array_push($matches, $tempArray);
		}

		// Return the matches page with the accepted and pending requests.
		return view('matches')->with('requestArray', $requestArray)->with('matches', $matches);
		
	}

}
