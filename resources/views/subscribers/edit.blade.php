@extends('layouts.app')

@section('title','MailerLite | Edit Subscriber')

@section('content')
    <div class="container text-center mt-5 col-lg-4">
        <h1>Edit Subscriber</h1>
        <form class="mt-4 row" action="/subscribers/{{ isset($subscriber->id) ? $subscriber->id : 0 }}" method="POST">
            @method('PUT')

            @include('layouts/flash-message')
            
            <div class="form-group text-left col-lg-12">
                <label for="email" >Email</label>
                <input id="email" name="email" type="text" class="form-control" value="{{ isset($subscriber->email) ? $subscriber->email : '' }}" readonly>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group text-left col-lg-12">
                <label for="name">Name</label>
                @php
                    if (old('name')):
                        $name = old('name');
                    else:
                        $name = isset($subscriber->name) ? : '';
                    endif;
                @endphp
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($subscriber->name) ? $subscriber->name : '' }}">
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
                        @php
                            if (old('country')):
                                $currentCountry = old('country');
                            endif;
                        @endphp

                        @foreach ($countries as $country)
                            <option value="{{ $country['name'] }}"
                                {{ $currentCountry == $country['name'] ? 'selected' : old('country') }}>
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
                <button type="submit" class="col-md-3 btn btn-primary btn-lg ">Update</button>
            </div>
        </form>
    </div>
@endsection