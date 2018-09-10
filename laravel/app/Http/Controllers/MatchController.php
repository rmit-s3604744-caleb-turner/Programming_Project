<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\User;
use MovieBuffs\UserDetail;
use MovieBuffs\UserPreference;
use DB;
use Illuminate\Support\Facades\Auth;
use Hootlex\Friendships\Traits\Friendable;

class MatchController extends Controller
{
	
	public function acceptFriendRequest(Request $request){
		$user = User::find(self::getId());
		$user->acceptFriendRequest(User::find($request->id));
		
		
		return redirect('matches')->with('success', 'Match request sent.');
	}
	
	public function denyFriendRequest(Request $request){
		$user = User::find(self::getId());
		$user->denyFriendRequest(User::find($request->id));
		
		
		return redirect('matches')->with('error', 'Match request denied.');
	}
	
	
	public function matches(){
		$user = User::find(self::getID());
		$user1Preferences = self::getPreferences($user->id);
		$maxScore = ($user1Preferences->location + $user1Preferences->movies + $user1Preferences->genre);
		
		
		$pending = $user->getFriendRequests();
		
		$requestArray = array();
		
		// for each new request
		foreach($pending as $request){
			
			// if the request is not from me
			if($pending[0]->sender_id != self::getID()){
				
				$user2 = User::find($pending[0]->sender_id);
				
				
				$similarity = self::findSimilarity($user->id, $user2->id);
			
				if(($similarity == 0) || ($maxScore == 0)){
					$percentage = 0 . '%';
				}else{
					$percentage = $similarity / $maxScore;
					$percentage = round((float)$percentage * 100) . '%';
				}
				$percentage = round((float)$percentage * 100) . '%';
				
				$tempArray = array();
				array_push($tempArray, $user2->name, $percentage, $user2->id);
				array_push($requestArray, $tempArray);
				
				
			}

		}
		
		
		$accepted = $user->getAcceptedFriendships();
		$matches = array();
		foreach($accepted as $match){
			
			if($match->sender_id == $user->id){
				$user2 = User::find($match->recipient_id);
			}else{
				$user2 = User::find($match->sender_id);
			}
				
				
				
			$tempArray = array();
			array_push($tempArray, $user2->name, $user2->id);
			array_push($matches, $tempArray);


		}

		return view('matches')->with('requestArray', $requestArray)->with('matches', $matches);
		
	}
	
	
	
	public function sendFriendRequest(Request $request){
		// send request
		
		$user = User::find(self::getID());
		$user2 = User::find($request->id);
		
		$user->befriend($user2);
		
		
		return redirect('matchlist')->with('success', 'Match request sent.');

	}
	
	
	
	
    public function index()
    {

		// get all 
		// $posts = Post::all();
		
		// get a specific post
		//$post = Post::where('title', '1st Post')->get();
		
		// alternative syntax
		//$posts = DB::Select('SELECT * FROM posts');
		
		// limiting
		//$posts = Post::orderBy('title', 'desc')->take(1)->get();
		
		
		// pagination
		//$posts = Post::orderBy('created_at', 'desc')->paginate(1);
		
		
		//$posts = Post::orderBy('created_at', 'desc')->get();
		
		// has an array of id's that are not the current user
		
		if(! Auth::check()){
			return redirect('/')->with('error', 'Unauthorised Page: Access Denied');
		}
		
		
		
		
		$userID = self::getID();
		
		
		$user = User::find($userID);
		if($user->preferenceSet==0){
			return redirect('preferences')->with('error', "You can't find matches until you have preferences set.");
		}
		

        $others = self::getOthers();
		
		
		$array = self::getMatchArray();
		
		return view('matchlist')->with('array', $array);
		
		
    }
	
	
	
	
	function getMatchArray(){

		$user1ID = self::getID();
	

		$user1Preferences = self::getPreferences($user1ID);
		$maxScore = ($user1Preferences->location + $user1Preferences->movies + $user1Preferences->genre);
		
		$scores = array();
		
		$userList = self::getOthers();
		
		$self = User::find($user1ID);
		$friends = $self->getFriends();
		
		
		
		foreach($userList as $user2ID){
			
			// check to make sure they are not in the accepted friends
			
			if(! $friends->contains('id', $user2ID)){
				
				
				$user2Name = self::getName($user2ID);
			
				$similarity = self::findSimilarity($user1ID, $user2ID);
				
				
				$percentage = $similarity / $maxScore;
				$percentage = round((float)$percentage * 100) . '%';
						
				$tempArray = array();
				array_push($tempArray, $user2Name, $percentage, $user2ID);
				array_push($scores, $tempArray);
				
				
			}
			

			
		}

		// a bubblesort method that sorts the array.
		usort($scores, "self::callbackSort");
			
		// can restrict the array to top 10 results or so here
		return $scores;
			
	}


	// Means of bubblesorting the array so that the highest % is 1st.
	function callbackSort($a, $b){
		return ($a[1] >= $b[1]) ? -1 : 1;
	}
	
	
	
	function findSimilarity($userID, $user2ID){
	
		$user1Details = self::getDetails(self::getID());
		$user1Preferences = self::getPreferences(self::getID());
		
		$user2Details = self::getDetails($user2ID);

		$genreSimilarity = self::genreSimilarity($user1Details, $user2Details);
		$movieSimilarity = self::movieSimilarity($user1Details, $user2Details);
		$locationSimilarity = self::locationSimilarity($user1Details, $user2Details);
		

		$genrePreference = $user1Preferences->genre;
		$moviePreference = $user1Preferences->movies;
		$locationPreference = $user1Preferences->location;
		
		$genreSimilarity *= $genrePreference;
		$movieSimilarity *= $moviePreference;
		$locationSimilarity *= $locationPreference;
		
		$score = $genreSimilarity + $movieSimilarity + $locationSimilarity;

		return $score;
		
	}

	
	
	function findSpecificGenreSimilarity($genre, $user1Details, $user2Details){
		
		$similarity = 0;
		$user1Like = $user1Details->$genre;
		$user2Like = $user2Details->$genre;

		$difference = abs($user1Like - $user2Like);
		$difference *= 0.2;
		$difference = 1-$difference;
			
		$similarity+= $difference;
		
		return $similarity;
		
		
	}
	
	
	function genreSimilarity($user1Details, $user2Details){
		
		$similarity = 0;
		
		$similarity += self::findSpecificGenreSimilarity('genreAction', $user1Details, $user2Details);
		$similarity += self::findSpecificGenreSimilarity('genreMystery', $user1Details, $user2Details);
		$similarity += self::findSpecificGenreSimilarity('genreHorror', $user1Details, $user2Details);

		$similarity /= 3;
		
		return $similarity;
	}
	
	
	
	function movieSimilarity($user1Details, $user2Details){
	
		$text = $user1Details->movies;
		$text = strtolower($text);

		$othertext = $user2Details->movies;
		$othertext = strtolower($othertext);
		
		if(strcmp($text, $othertext) == 0){
			return 1;
		}else{
			return 0;
		}
	
	}
	
	
	private function locationSimilarity($user1Details, $user2Details){
		$text = $user1Details->location;
		$text = strtolower($text);

		$othertext = $user2Details->location;
		$othertext = strtolower($othertext);
		
		if(strcmp($text, $othertext) == 0){
			return 1;
		}else{
			return 0;
		}

	}

	
	private function getID(){
		return auth()->user()->id;
	}
	
	private function getDetails($id){
		return UserDetail::find($id);
	}
	
	private function getPreferences($id){
		return UserPreference::find($id);
	}
	
	private function getOthers(){
		$userID = self::getID();
		
		
		$otherUsers = User::where('id', '!=', $userID)->where('preferenceSet', '1')->pluck('id')->toArray();
		
		return $otherUsers;
	}
	
	private function getName($id){
		return User::where('id', $id)->get()->pluck('name');
	}
	
}
