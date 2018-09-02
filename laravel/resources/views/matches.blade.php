@extends('layouts.app')


@section('content')
	<h1> Your Matches <h1>
	
	@if(count($array) > 0)
		
			
			
		<table class="table table-striped">
		
			<tr>
			
				<th>Matches</th>
				
			</tr>
			
			@foreach($array as $user)
			
				<tr>
			
					<td>{{$user[0][0]}}</td>
					<td>{{$user[2]}}</td>
				
				</tr>
			@endforeach
			
		</table>
		
	
	@else
		<p> There are no other users. </p>
	@endif
@endsection