<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\User;
use MovieBuffs\UserDetail;
use MovieBuffs\UserPreference;
use DB;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
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
		
		
		
        $others = self::getOthers();
		$userID = self::getID();
		
		$array = self::getMatchArray();
		
		return view('matches')->with('array', $array);
		
		
    }
	
	
	
	
	function getMatchArray(){

		$user1ID = self::getID();
	

		$user1Preferences = self::getPreferences($user1ID);
		$maxScore = ($user1Preferences->location + $user1Preferences->movies + $user1Preferences->genre);
		
		$scores = array();
		
		$userList = self::getOthers();
		
		foreach($userList as $user2ID){
			
			$user2Name = self::getName($user2ID);
			
			$similarity = self::findSimilarity($user1ID, $user2ID);
			
			
			$percentage = $similarity / $maxScore;
			$percentage = round((float)$percentage * 100) . '%';
					
			$tempArray = array();
			array_push($tempArray, $user2Name, $similarity, $percentage);
			array_push($scores, $tempArray);
			
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
		$otherUsers = User::where('id', '!=', $userID)->pluck('id')->toArray();
		
		return $otherUsers;
	}
	
	private function getName($id){
		return User::where('id', $id)->get()->pluck('name');
	}
	
}
