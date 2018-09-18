@extends('layouts.app')

@section('content')
<div class= "msgs">
    <h1>Start a conversation</h1>
    <form action="{{ route('messages.store') }}" method="post">
        {{ csrf_field() }}
        <div class="col-md-6">
            <!-- Subject Form Input -->
            <div class="form-group">
                <label class="control-label">Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Subject"
                       value="{{ old('subject') }}">
            </div>

            <!-- Message Form Input -->
            <div class="form-group">
                <label class="control-label">Message</label>
                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
            </div>

			<h2> Add People </h2>
            @if(sizeof($users) > 0)
                <div class="checkbox">
			
                    @foreach($users as $user)
                        <label title="{{ $user[0] }}"><input type="checkbox" name="recipients[]"
                                                                value="{{ $user[1] }}">{!!$user[0]!!}</label>
                    @endforeach
					
					
                </div>
            @endif
    
            <!-- Submit Form Input -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </div>
    </form>
</div>
@stop