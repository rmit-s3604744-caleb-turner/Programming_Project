@extends('layouts.app')


@section('content')
	<h1> Pending Matches </h1>
	
	@if(count($requestArray) > 0)
		
			
			
		<table class="table table-striped">
		
			<tr>
			
				<th>Name</th>
				<th>Match Percentage</th>
				
			</tr>
			
			<tr>
			
				@foreach($requestArray as $request)
					<td>{{$request[0]}}</td>
					<td>{{$request[1]}}</td>
					
					
					
					<td>
						<form method="post" action="acceptRequest">
							<input type="hidden" name="id" value={{$request[2]}} />
							{{ csrf_field() }}
							<button type="submit">Accept</button>
						</form>
					</td>
					
					<td>
						<form method="post" action="denyRequest">
							<input type="hidden" name="id" value={{$request[2]}} />
							{{ csrf_field() }}
							<button type="submit">Deny</button>
						</form>
					</td>
					
					
					
				@endforeach
			
			</tr>
			
			
		</table>
		
	
	@else
		<p> No match requests. </p>
	@endif
	
	
	
	
	<h1> Matches </h1>
	
	
	<table class="table table-striped">
		
			<tr>
			
				<th>Name</th>
				
			</tr>
			@foreach($matches as $match)
			<tr>
			
					<td>{{$match[0]}}</td>
					<td><a href="/profile/{{$match[1]}}" class=="btn btn-default">Leave Rating</a></td>
					
					
					
				
			
			</tr>
			@endforeach
			
		</table>
	
	
	
@endsection