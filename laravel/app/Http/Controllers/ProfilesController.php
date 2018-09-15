<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cmgmyr\Messenger\Traits\Messagable;
use Hootlex\Friendships\Traits\Friendable;
use Laravelista\Comments\Commenter;
use Laravelista\Comments\Commentable;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;
use MovieBuffs\User;
use MovieBuffs\Profile;
use DB;
use Illuminate\Support\Facades\Auth;


class ProfilesController extends Controller
{
	
	
	
	public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
	
	
    public function index(){
		
		return view('profile.index');
	}
	
	
	
	
	public function show($id){
	
		$profile = Profile::find($id);

		
		$reviewCheck = DB::table("ratings")->where("rateable_id", $id)->get();


		
		if(sizeof($reviewCheck) == 0){

			$rating = '0';
		}else{
			$rating = $profile->averageRating;
		}
		
		$user = User::find($id);
		$name = $user->name;
		
		
		// check if there are any comments
		// if there are, then get the comment, the commenter's name and the score
		$comments = DB::table("comments")->where("commentable_id", $id)->get();
		
		
		$userComments = array();
		
		foreach($comments as $comment){
			$commenter_id = $comment->commenter_id;
			
			
			$name = User::find($commenter_id)->value('name');
			
			if($reviewCheck)
			$score = DB::table("ratings")->where("user_id", $commenter_id)->where("rateable_id", $id)->value('rating');
			
			$textComment = $comment->comment;
			
			$tempArray = array();
			array_push($tempArray, $name, $score, $textComment);
			
			array_push($userComments, $tempArray);
			
			
		}
		
		
		
		
		
		return view('profile.index')->with('name', $name)->with('rating', $rating)->with('id',$id)->with('userComments', $userComments);

		
	}
	
	
	public function addRating(Request $request){
		
		$profileID = $request->id;
		$profile = Profile::find($profileID);
		$user_id = Auth::id();
		
		$comment = $request->message;
		if (strlen($comment) != 0){
			
			$userComment = new \Laravelista\Comments\Comment;
			
			
			$userComment->commenter()->associate($user_id);
			$userComment->commentable()->associate($profile);
			$userComment->comment = $comment;
			$userComment->save();
			
			
			
			
			return redirect('matches')->with('error', 'test');
			
		}
		
		
		
		$alreadyRated = DB::table("ratings")->where("user_id", $user_id)->where("rateable_id", $profileID);
		if($alreadyRated){
			return redirect('matches')->with('error', 'You have already rated this person');
		}
		
		
		
		$rating = new \willvincent\Rateable\Rating;
		$rating->rating = $request->rating;
		$rating->user_id = Auth::id();
		$profile->ratings()->save($rating);
		
		
		
		// check if comment is enabled
		$comment = $request->message;
		if (strcmp($comment,"") != 0){
			
			$userComment = new Comment;
			
			
		}
		
		
		return redirect('matches')->with('success', "Rated user");
	}
	
}
