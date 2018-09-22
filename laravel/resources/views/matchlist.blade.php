@extends('layouts.app')


@section('content')
	<h1> Potential Matches </h1>
	
	@if(count($array) > 0)
	
		<table class="table table-striped">
			<tr>
				<th>Name</th>
				<th>Match % </th>
				<th>Rating </th>
				<th>

					<form method="get" action="refine">
							<input type="number" name="threshold" min="0" max="5" value="0"/>						

							<button type="submit">Limit Results</button>
					</form>
				
				</th>
				
			</tr>
			
			@foreach($array as $user)
				<tr>
					<td>
						<a href="/profile/{{$user[2]}}">{{$user[0][0]}}</a>
					</td>
					
					<td>{{$user[1]}}</td>
					<td>{{$user[4]}}</td>
					<td>
						<form method="post" action="sendRequest">
							<input type="hidden" name="id" value={{$user[2]}} />
							{{ csrf_field() }}
							<button type="submit">Try to Match</button>
						</form>
					</td>
					
				</tr>
			@endforeach
			
		</table>
		
	
	@else
		<p> There are no users who meet your requirement. </p>
		<table class="table table-striped">
			<tr>
				<th>Name</th>
				<th>Match % </th>
				<th>Rating </th>
				<th>

					<form method="get" action="refine">
							<input type="number" name="threshold" min="0" max="5" value="0"/>						

							<button type="submit">Limit Results</button>
					</form>
				
				</th>
			</tr>
		</table>
	
	@endif
@endsection