@extends('layouts.app')

@section('title','MailerLite | Add Subscriber')

@section('content')
    <div class="container text-center mt-5 col-lg-4">
        <h1>Add Subscriber</h1>
        <form class="mt-4 row" action="/subscribers" method="POST">
            @if(session('message'))
                <div class="alert {{ session('messageClass') }} col-lg-12" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            
            <div class="form-group text-left col-lg-12">
                <label for="email" >Email</label>
                <input id="email" name="email" type="text" class="form-control" value="{{ session('email') }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group text-left col-lg-12">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ session('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group text-left col-lg-12">
                <label for="name">Country</label>
                <select id="country" name="country" class="form-control">
                    <label for="name">Name</label>

                    @if (empty($countries))
                        <option value="">
                            No countries available. Please check countries.json
                        </option>
                    @else
                        @foreach ($countries as $country)
                            <option value="{{ $country['name'] }}"
                                {{ session('country') == $country['name'] ? 'selected' : ''}}>
                                {{ $country['name'] }}
                            </option>
                        @endforeach
                    @endif

                </select>
                @error('country')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="col-md-3 btn btn-primary btn-lg ">Submit</button>
            </div>
        </form>
    </div>
@endsection