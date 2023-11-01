@extends('website.layouts.app')
@section('css')
    <link rel="stylesheet" href="{{asset('website/css/auth.css')}}?ver={{ filemtime(public_path('website/css/auth.css')) }}">
@endsection
@section('content')
<div class="container">
    <div class="page-content">
        <form action="{{route('login')}}" method="post" class="auth-form">
            @csrf
            <h2 class="form-title">{{__('Login')}}</h2>
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
            <div class="form-check-row">
                <input class="form-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            <div class="form-button-row">
                <button type="submit" id="login-button">
                    {{__('Login')}}
                </button>
            </div>
        </form>

        <div class="info">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}">{{ __('Register now!') }}</a>
        </div>
    </div>
</div>
@endsection
