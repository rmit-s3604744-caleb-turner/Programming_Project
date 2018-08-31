@extends('layouts.app')


@section('content')
	<h1> Create Post <h1>
	{!! Form::open(['action' => 'PostsController@store', 'method' => 'POST']) !!}
		
		<div class="form-group">
			
			{{Form::label('title', 'Enter a Title')}}
			
			{{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title'])}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('body', 'Body Text')}}
			<!-- Don't change the ID of the text area, that gives it the editing functionality -->
			{{Form::textarea('body', '', ['id' =>'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Write Something'])}}
			
		</div>
		
		{{Form::submit('Submit', ['class'=>'btn btn->primary'])}}
		
	{!! Form::close() !!}
	
@endsection