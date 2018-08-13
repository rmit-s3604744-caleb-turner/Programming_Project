
@extends('layouts.app')

@section('content')
	<h1> Match <h1>
	
<?php

include(resource_path() . '..\lang\readFile.php');

$array = readIntoArray();

foreach($array AS $row){
	echo $row[0] . "  |  " . $row[2] . "<br>";
}

?>
@endsection

