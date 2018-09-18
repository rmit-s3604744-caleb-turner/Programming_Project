@extends('layouts.app')


@section('content')
<div class= "Pref">
	<h1 class= "Pre"> Edit Preferences</h1>

	
	{!! Form::open(['action' => ['DetailsController@update', $details[0]], 'method' => 'POST']) !!}
		
		<h2> Your Details </h2>
		
		<div class="form-group">
			
			{{Form::label('location', 'Location')}}
			<br>
			{{Form::text('location', $details[1]->location)}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('movie', 'Favourite Movie')}}
			<br>
			{{Form::text('movie', $details[1]->movies)}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('genreAction', 'How much do you like Action movies? (0-5)')}}
			<br>
			{{Form::number('genreAction', $details[1]->genreAction, ['min'=>0,'max'=>5])}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('genreMystery', 'How much do you like Mystery movies? (0-5)')}}
			<br>
			{{Form::number('genreMystery', $details[1]->genreMystery, ['min'=>0,'max'=>5])}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('genreHorror', 'How much do you like Horror movies? (0-5)')}}
			<br>
			{{Form::number('genreHorror', $details[1]->genreHorror, ['min'=>0,'max'=>5])}}
			
		</div>
		
		
		<br>
		<br>
		
		<h2> Search Preferences </h2>
		
		
		
		<div class="form-group">
			
			{{Form::label('locationImp', 'Location Importance (0-5)')}}
			<br>
			{{Form::number('locationImp', $details[2]->location, ['min'=>0,'max'=>5])}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('movieImp', 'Movie Importance (0-5)')}}
			<br>
			{{Form::number('movieImp', $details[2]->movies, ['min'=>0,'max'=>5])}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('genreImp', 'Genre Importance (0-5)')}}
			<br>
			{{Form::number('genreImp', $details[2]->genre, ['min'=>0,'max'=>5])}}
			
		</div>
		
		
		
		{{Form::hidden('_method', 'PUT')}}
		
		{{Form::submit('Submit', ['class'=>'btn btn->primary'])}}
		
	{!! Form::close() !!}
	
	</div>
	
	
	
	
@endsection
