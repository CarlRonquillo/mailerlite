@extends('layouts.app')

@section('content')

    <div class="container text-center mt-5">
        <h1>Subscribers!</h1>

        @if(!empty($message))
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @endif
    
        @forelse ($data['subscribers'] as $subscriber)
            {{ $subscriber->email }}
        @empty
            <p>No subscribers found</p>
        @endforelse
    </div>
    
@endsection