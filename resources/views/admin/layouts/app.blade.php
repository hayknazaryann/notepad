<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(app()->environment(['production']))
        {{-- in "production" environment --}}
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @endif

    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/themes/dark.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body data-theme="dark">

<!-- ======= Header ======= -->
@include('admin.layouts.header')
<!-- ======= Sidebar ======= -->
@include('admin.layouts.sidebar')
<!-- End Sidebar-->

<main id="main" class="main">
    @include('.admin.partials.flash_messages')
    @yield('content')
</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('dashboard/js/plugins/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('dashboard/js/plugins/sweetalert2.all.js') }}"></script>
<script src="{{ asset('js/general.js') }}"></script>
<script src="{{ asset('dashboard/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@yield('js')
</body>

</html>
