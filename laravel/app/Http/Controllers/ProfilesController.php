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

// Controller that handles user profiles.
class ProfilesController extends Controller
{
	// Constructor Class
	public function __construct(){
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
	
	// Function that displays the default profile page.
    public function index(){
		return view('profile.index');
	}
	
	
	// Function that displays a given user's profile.
	public function show($id){
	
		$profile = Profile::find($id);
		
		// Checking if the profile has any ratings.
		$reviewCheck = DB::table("ratings")->where("rateable_id", $id)->get();
		if(sizeof($reviewCheck) == 0){
			$rating = '0';
		}else{
			$rating = $profile->averageRating;
		}
		
		// Getting the user associated with the profile's details.
		$user = User::find($id);
		$name = $user->name;
		
		// Getting the set of comments left on a user's profile.
		$comments = DB::table("comments")->where("commentable_id", $id)->get();
		$userComments = array();
		
		// For each review...
		foreach($comments as $comment){
			
			// Get the comment.
			$commenter_id = $comment->commenter_id;
			$textComment = $comment->comment;
			// Get the name of the commenter.
			$name = User::find($commenter_id)->value('name');
			// Get their given score.
			$score = DB::table("ratings")->where("user_id", $commenter_id)->where("rateable_id", $id)->value('rating');
			
			// Add it to the array.
			$tempArray = array();
			array_push($tempArray, $name, $score, $textComment);
			array_push($userComments, $tempArray);
		}
		
		// Checking if the current user has matched with the profile.
		$self = User::find(auth()->user()->id);
		$friends = $self->getFriends();
		
		// If they have, then they can leave a review.
		$canReview = $friends->where('id', $id);
		if(count($canReview)){
			$canReview = 1;
		}else{
			$canReview = 0;
		}

		// Return the profile with the needed information.
		return view('profile.index')
			->with('name', $name)
			->with('rating', $rating)
			->with('id',$id)
			->with('userComments', $userComments)
			->with('canReview',$canReview);
			
	}
	
	
	// Function to leave a rating on the current profile.
	public function addRating(Request $request){
		
		// Get the profile.
		$profileID = $request->id;
		$profile = Profile::find($profileID);
		
		// Get the user.
		$user_id = Auth::id();

		// Check if the user has already left a review.
		$alreadyRated = DB::table("ratings")->where("user_id", $user_id)->where("rateable_id", $profileID);
		
		// Denying multiple reviews from the same user (to prevent people forcing ratings up/down).
		if($alreadyRated){
			return redirect('matches')->with('error', 'You have already rated this person');
		}
		
		// Check if the user left a comment with the score.
		$comment = $request->message;
		// If they did...
		if (strlen($comment) != 0){
			// Create a new comment in the database.
			$userComment = new \Laravelista\Comments\Comment;
			// Add the comment's details.
			$userComment->commenter()->associate($user_id);
			$userComment->commentable()->associate($profile);
			$userComment->comment = $comment;
			
			// Save the comment.
			$userComment->save();
		}
		
		// Create a new rating in the database.
		$rating = new \willvincent\Rateable\Rating;
		$rating->rating = $request->rating;
		$rating->user_id = Auth::id();
		// Save the rating.
		$profile->ratings()->save($rating);
		
		// Send them back to the matches page with a confirmation message.
		return redirect('matches')->with('success', "Rated user");
	}
	
}
