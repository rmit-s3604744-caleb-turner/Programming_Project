<?php 

// Reads data into an array.
function readIntoArray(){

	return getMatchArray();
}


function getSQL($SQL){
	$servername = "localhost";
	$username = "root";
	$password = "test";
	$dbname = "matchdatabase";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$result = $conn->query($SQL);
	
	return $result;
	
}


function getDetails($userID){
	

	$sql = "SELECT * FROM userdetail WHERE ID=".$userID;
	$result = getSQL($sql);
	
	$detailsArray = array();
	
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		array_push($detailsArray, $row["ID"], $row["name"], $row["location"], $row["movies"], 
									$row["action"], $row["horror"], $row["mystery"]);
		
	}
	
	return $detailsArray;
}


function getPrefs($userID){

	$sql = "SELECT * FROM userpreference WHERE ID=".$userID;	
	$result = getSQL($sql);
	
	$prefsArray = array();
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		array_push($prefsArray,$row["location"], $row["movies"], $row["genres"]);
	}
	
	return $prefsArray;
}


function getUsers(){
	

	$sql = "SELECT * FROM userpreference";	
	$result = getSQL($sql);
	
	$userList = array();
	
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			array_push($userList,$row["ID"]);
		}
	}
	
	return $userList;
}


// creates an array of matches
function getMatchArray(){
	

	
	$user1Details = getDetails(1);

	$user1Preferences = getPrefs(1);

	$length = count($user1Details);
	$scores = array();
	
	$maxScore = ($user1Preferences[0] + $user1Preferences[1] + $user1Preferences[2]);
	
	
	$userList = getUsers();
	
	foreach($userList as $user){
		if($user != $user1Details[0]){
			$user2Details = getDetails($user);
			
			
			$similarity = findSimilarity($user1Details, $user1Preferences, $user2Details);
			
			
			$percentage = $similarity / $maxScore;
			$percentage = round((float)$percentage * 100) . '%';
					
			$tempArray = array();
			array_push($tempArray, $user, $similarity, $percentage);
			array_push($scores, $tempArray);
		}
	}
	

	// a bubblesort method that sorts the array.
	usort($scores, "callbackSort");
	
	// can restrict the array to top 10 results or so here
	return $scores;
	
}


// Means of bubblesorting the array so that the highest % is 1st.
function callbackSort($a, $b){
	return ($a[1] >= $b[1]) ? -1 : 1;
}





// Combined similarity function.
function findSimilarity($user1Details, $user1Preferences, $user2Details){
	

	$genreSimilarity = findGenreSimilarity($user1Details, $user2Details);
	$movieSimilarity = findMovieSimilarity($user1Details, $user2Details);
	$cinemaSimilarity = findCinemaSimilarity($user1Details, $user2Details);
	

	$genrePreference = $user1Preferences[2];
	$moviePreference = $user1Preferences[1];
	$cinemaPreference = $user1Preferences[0];
	
	
	$score = ($genreSimilarity * $genrePreference) 
			+ ($movieSimilarity * $moviePreference) 
			+ ($cinemaSimilarity * $cinemaPreference);

	return $score;
	
}


// Function to find the similarity between 2 users and their preferred genres.
function findGenreSimilarity($user1Details, $user2Details){
	$length = count($user1Details) -1;
	$skipEntries = 4;

	$similarity = 0;
	
	for($i=$skipEntries; $i<$length; $i++){
		
		$user1Like = (int)$user1Details[$i][0];
		$user2Like = (int)$user2Details[$i][0];

		$difference = abs($user1Like - $user2Like);
		$difference *= 0.2;
		$difference = 1-$difference;
		
		$similarity+= $difference;
		
		
	}
	
	$similarity /= $length - $skipEntries;

	return $similarity;
}


// Fucntion to find similarity between 2 users and the movies they like
function findMovieSimilarity($user1Details, $user2Details){
	
	$user1Movies = explode('#', $user1Details[3]);
	$user2Movies = explode('#', $user2Details[3]);
	
	$user1LikedMovies = count($user1Movies);
	
	$total = 0;
	
	foreach($user1Movies as $user1Movie){
		foreach($user2Movies as $user2Movie){
			
			if(strcmp($user1Movie, $user2Movie) == 0){				
				$total++;
			}
		
			
		}
	}
	

	$total /= $user1LikedMovies;
	
	
	return $total;

}



// Fuction to determine if the users have a similar location
function findCinemaSimilarity($user1Details, $user2Details){
	
	$text = $user1Details[2];
	$text = strtolower($text);
	
	$othertext = $user2Details[2];
	$othertext = strtolower($othertext);
	
	if(strcmp($text, $othertext) == 0){
		return 1;
	}else{
		return 0;
	}
	
}

?>