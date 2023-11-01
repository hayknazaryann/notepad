@extends('website.layouts.app')
@section('css')
    <link rel="stylesheet" href="{{asset('website/css/auth.css')}}?ver={{ filemtime(public_path('website/css/auth.css')) }}">
@endsection
@section('content')
    <div class="container">
        <div class="page-content">
            <form action="{{route('register')}}" method="post" class="auth-form">
                @csrf
                <h2 class="form-title">{{__('Register')}}</h2>
                <div class="form-row">
                    <label for="name">{{__('Full Name')}}</label>
                    <input type="text" id="name" name="name" class="@error('name') is-invalid @enderror" value="{{old('name')}}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-row">
                    <label for="email">{{__('E-mail Address')}}</label>
                    <input type="text" id="email" name="email" class="@error('email') is-invalid @enderror" value="{{old('email')}}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-row">
                    <label for="password">{{__('Password')}}</label>
                    <input type="password" id="password" name="password" class="@error('password') is-invalid @enderror" value="">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-row">
                    <label for="password_confirmation">{{__('Confirm password')}}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="@error('password_confirmation') is-invalid @enderror" value="">
                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-button-row">
                    <button type="submit" id="login-button">
                        {{__('Register')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
