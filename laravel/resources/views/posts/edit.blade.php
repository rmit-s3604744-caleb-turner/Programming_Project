@extends('layouts.app')


@section('content')
	<h1> Edit Post </h1>
	{!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST']) !!}
		
		<div class="form-group">
			
			{{Form::label('title', 'Enter a Title')}}
			
			{{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
			
		</div>
		
		<div class="form-group">
			
			{{Form::label('body', 'Body Text')}}
			<!-- Don't change the ID of the text area, that gives it the editing functionality -->
			{{Form::textarea('body', $post->body, ['id' =>'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Write Something'])}}
			
		</div>
		
		{{Form::hidden('_method', 'PUT')}}
		
		{{Form::submit('Submit', ['class'=>'btn btn->primary'])}}
		
	{!! Form::close() !!}
	
@endsection