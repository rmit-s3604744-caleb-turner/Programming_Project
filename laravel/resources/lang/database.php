<?php


function addUser($email, $password){
	
	$servername = "localhost";
	$username = "root";
	$password = "test";
	$dbname = "matchdatabase";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$SQL = "INSERT INTO userlogin VALUES (DEFAULT,'".$email."', '".$password."')";
	$result = $conn->query($SQL);
	
	$latestID = $conn->insert_id;
	

	$SQL = "INSERT INTO userdetail VALUES (".$latestID.", ' ', ' ', ' ',0,0,0)";
	$result = $conn->query($SQL);
	
	$SQL = "INSERT INTO userpreference VALUES (".$latestID.",0,0,0)";
	$result = $conn->query($SQL);

}

function areDetailsSet($ID){
	$sql = "SELECT * FROM userdetail WHERE ID=".$ID;
	
	$result = getSQL($sql);
	$array = $result->fetch_assoc();
	
	if(strlen($array["name"]) != 0){
		if(strlen($array["location"]) != 0){
			if(strlen($array["movies"]) != 0){
				return "true";
			}
		}
	}
	return "false";
}

function arePreferencesSet($ID){
	$sql = "SELECT * FROM userpreference WHERE ID=".$ID;
	
	$result = getSQL($sql);
	$array = $result->fetch_assoc();
	
	if(is_null($array["location"]) || is_null($array["movies"]) || is_null($array["genres"])){
		return "false";
	}
	
	return "true";
}


function canLogin($email, $password){
	$sql = "SELECT * FROM userlogin WHERE email=".$email."  AND password=".$password;
	$result = getSQL($sql);
	if(mysql_num_rows($result)==0){
		return 0;
	}else{
		$array = $result->fetch_assoc();
		$ID = $array["ID"];
		return $ID;
	}
}

function doesAccountExist($email){

	$sql = "SELECT COUNT(*) FROM userlogin WHERE email='".$email."'";

	$result = getSQL($sql);
	
	if($result->num_rows > 0){
		return "true";
	}else{
		return "false";
	}
}


function setPreferences($ID, $location, $movies, $genres){
	
	$sql = "UPDATE userpreference SET location=".$location.", movies=".$movies.
			", genres=".$genres." WHERE ID=".$ID;
	
	getSQL($sql);
}


function setDetails($ID, $name, $location, $movies, $action, $horror, $mystery){
	
	$sql = 	"UPDATE userdetail SET name='".$name."', location='".$location."', movies='".$movies.
			"', action=".$action.", horror=".$horror.", mystery=".$mystery.
			" WHERE ID=".$ID;
	
	getSQL($sql);
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



?>