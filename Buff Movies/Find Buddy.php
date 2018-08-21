<!DOCTYPE html>
<html>
<body>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>





<div class = "nav">

	<ul>
	
  		<a href="#home" class= "home">Home</a>
  		<a href="Find Buddy.php"class= "find">Find a Buddy</a>
  		<a href="#contact"class= "movies">Movies</a>
  		<a href="#about"class= "forum">Forum</a>
	</ul>

</div> 

<?php

include('readFile.php');

$array = readIntoArray();

foreach($array AS $row){
	echo $row[0] . "  |  " . $row[2] . "<br>";
}

?>


</body>
</html>
