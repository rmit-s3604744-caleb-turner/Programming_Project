@extends('layouts.app')

@section('content')
    <div class="col-md-6">
        <h1>{{ $thread->subject }}</h1>
		<br>
        @each('messenger.partials.messages', $thread->messages, 'message')

        @include('messenger.partials.form-message')
    </div>
@stop