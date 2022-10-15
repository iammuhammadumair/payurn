<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}




    <!-- Favicon -->
    <link rel="icon" href="{{ get_option('logo_setting', true)->favicon ?? null }}"/>

    <!-- Import css File -->
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/owl.carousel.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('frontend_assets/css/menu.css') }}"> --}}

    <link rel="stylesheet" href="{{ asset('plugins/toastify-js/src/toastify.css') }}">

    @yield('css')
    @stack('css')

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('frontend_assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/custom.css') }}">

</head>

<body @class([$bodyClass ?? null])>

@yield('body')

<!-- **** All JS Files ***** -->
<script src="{{ asset('frontend_assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend_assets/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend_assets/js/bootstrap.min.js') }}"></script>
<!-- Custom Js -->
<script src="{{ asset('frontend_assets/js/aos.js') }}"></script>
<script src="{{ asset('frontend_assets/js/jquery.easing.min.js') }}"></script>
<script src="{{ asset('frontend_assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend_assets/js/main-menu.js') }}"></script>

<!-- Active -->
<script src="{{ asset('frontend_assets/js/default-assets/active.js') }}"></script>

<script src="{{ asset('plugins/toastify-js/src/toastify.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>

@yield('script')
@stack('script')

<script src="{{ asset('plugins/custom/custom.js') }}"></script>
<script src="{{ asset('plugins/custom/form.js') }}"></script>
<script src="{{ asset('plugins/custom/Notify.js') }}"></script>
<script src="{{ asset('frontend_assets/js/custom.js') }}"></script>

</body>

</html>
