<?php 

function readIntoArray(){
	
	$data = file_get_contents('users.txt');
	$data = explode("\n", $data);
	
	unset($data[count($data) -1]);

	$array = array();
	
	foreach($data AS $row){
		$array[] = explode('|', $row);
	}

	$user1 = $array[0];
	$user2 = $array[6];
	$user1Preferences = $array[1];
	
	
	
	
	
	return getMatchArray($array, $user1[0]);
}




function getMatchArray($array, $userID){
	
	foreach($array AS $row){
		if((int)$row[0] == $userID){
			$user1Details = $row;
			break;
		}
	}
	
	foreach($array AS $row){
		if((int)$row[0] == $userID){
			if($row !== $user1Details){
				$user1Preferences = $row;
				break;
			}
		}
		
	}
	
	
	$length = count($user1Details);
	$scores = array();
	
	
	foreach($array AS $row){
		if($row !== $user1Details){
			if($row !== $user1Preferences){
				if(count($row) == count($user1Details)){

					$similarity = findSimilarity($user1Details, $user1Preferences, $row);
					$tempArray = array();
					array_push($tempArray, $row[0], $row[1], $similarity);
					array_push($scores, $tempArray);

				}
			}
			
		}

	}
	
	
	usort($scores, "callbackSort");
	
	
	return $scores;
}


function callbackSort($a, $b){
	return ($a[2] >= $b[2]) ? -1 : 1;
}






function findSimilarity($user1Details, $user1Preferences, $user2Details){
	
	echo $user2Details[1];
	
	$genreSimilarity = findGenreSimilarity($user1Details, $user2Details);
	$movieSimilarity = findMovieSimilarity($user1Details, $user2Details);
	$cinemaSimilarity = findCinemaSimilarity($user1Details, $user2Details);
	

	$genrePreference = (int)$user1Preferences[3][0];
	$moviePreference = (int)$user1Preferences[2][0];
	$cinemaPreference = (int)$user1Preferences[1][0];
	
	
	$score = ($genreSimilarity * $genrePreference) 
			+ ($movieSimilarity * $moviePreference) 
			+ ($cinemaSimilarity * $cinemaPreference);

	return $score;
	
}


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