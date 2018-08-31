@extends('layouts.app')

@section('content')
	<h1> Log In <h1>
@endsection


<form method="post" action="{{URL::to('/tryLogin')}}">
	<input type="text" name="email" value="" placeholder="Email" required>
	<input type="text" name="password" value="" placeholder="Password" required>
	<input type="hidden" name="_token" value={{csrf_token()}}>
	<button type="submit" name="logButton">Log In </button>

</form>