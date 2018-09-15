@extends('layouts.app')

@section('content')

<h1> {{$name}}'s Profile </h1>



<!-- If you want different visual stars, you gotta do an (at)if {{$rating}} == 1, load image of 1 star -->

<h2> Rating : {{$rating}} stars</h2>


	<form action="{{action('ProfilesController@addRating')}}" method="post">
        {{ csrf_field() }}
        <div class="col-md-6">
            <!-- Review Score Form Input -->
            <div class="form-group">
                <label class="control-label">Leave a Score</label>
                <input type="number" class="form-control" name="rating" min="1" max="5"
                       value= "1">
            </div>
			
           
		   
		    <!-- Comment Form Input -->
		   
		   
		   
		   
            <div class="form-group">
                <label name="message">Leave a comment here:</label>
				<br>
                <textarea name="message"></textarea>
            </div>
      
		   
		   
		   <input type="hidden" name="id" value={{$id}}>
		   
		   
    
            <!-- Submit Form Input -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </div>
    </form>


	
	@foreach ($userComments as $comment)
		
		{{$comment[0]}}
		<br>
		{{$comment[1]}}
		<br>
		{{$comment[2]}}
		
	@endforeach


@endsection