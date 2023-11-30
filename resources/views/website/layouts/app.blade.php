<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- SEO --}}
    <title>{{$title ?? ''}}</title>
    <meta name="title" content="{{ $title ?? __('seo.title') }}">
    <meta name="og:title" content="{{ $title ?? __('seo.title') }}">
    <meta name="keywords" content="{{ $metaKeywords ?? __('seo.keywords') }}">
    <meta name="description" content="{{ $metaDesc ?? __('seo.description') }}">
    <meta name="og:description" content="{{ $metaDesc ?? __('seo.description') }}">
    <meta name="og:image" content="{{ $imgUrl ?? '' }}">
    <meta name="robots" content="noindex, nofollow"/>
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{config('app.url')}}">
    <meta name="analytics:site_domain" content="{{config('app.url')}}">
    <link rel="canonical" href="{{config('app.url')}}"/>
{{--    <link rel="shortcut icon" href="{{asset('images/favico.ico')}}">--}}


    @if(app()->environment(['production']))
        {{-- in "production" environment --}}
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"/>
    @endif



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"
          integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <link rel="stylesheet" href="{{ asset('vendors/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2/css/select2.min.css') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('vendors/sweetalert/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/sheet/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/main.css')}}?ver={{ filemtime(public_path('website/css/main.css')) }}">

{{--    <link rel="stylesheet" href="{{ asset("website/css/themes/". (Auth::user()->theme ?? 'light') .".css")}}?ver={{ filemtime(public_path('website/css/themes/'. (Auth::user()->theme ?? 'light') .'.css')) }}">--}}
    <link rel="stylesheet" href="{{ asset("website/css/themes/". (Auth::user()->theme ?? 'dark') .".css")}}?ver={{ filemtime(public_path('website/css/themes/'. (Auth::user()->theme ?? 'dark') .'.css')) }}">
    @yield('css')


</head>
<body>
<div id="app">
    @include('website.layouts.header')
    <main class="main-content">
        @yield('content')
    </main>z
    @include('website.partials.sheet')
</div>


<!-- Scripts -->
<script src="{{ asset('vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendors/plugins/jquery.min.js') }}"></script>
<script src="{{ asset('vendors/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendors/sweetalert/sweetalert2.min.js') }}"></script>
<script src="{{asset('js/general.js')}}?ver={{ filemtime(public_path('js/general.js')) }}" defer></script>
<script src="{{asset('website/js/main.js')}}?ver={{ filemtime(public_path('website/js/main.js')) }}" defer></script>
<script src="{{ asset('vendors/sheet/js/script.js') }}"></script>

@yield('js')
@yield('modals')
</body>
</html>
