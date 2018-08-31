<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8">
		<title> {{config('app.name', 'Movie Buffs')}} </title>
	</head>
	
	<body>
		@yield('content')
		<!--@include('include.sidebar')-->
		
		
		<div class="container">
		
			@include('include.messages')
			
		</div>
		
		
	</body>
</html>