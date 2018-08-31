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
		
		<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
		<script>
			CKEDITOR.replace( 'article-ckeditor' );
		</script>
	</body>
</html>