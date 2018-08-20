@extends('layouts.app')

@section('content')
	<h1> Home <h1>
@endsection


@section('sidebar')
	@parent
	<p> Appended to sidebar </p>
@endsection



<?php 

$servername = "localhost";
$username = "root";
$password = "test";

$conn = new mysqli($servername, $username, $password);
if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

echo "connected";

?>