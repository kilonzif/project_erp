<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AAU-MEL') }}</title>

    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

    <link rel="apple-touch-icon" href="{{asset('images/aau_icon.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/aau_icon.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
          rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/vendors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}">
    @stack('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/ui/prism.min.css')}}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    @stack('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
    <!-- END STACK CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/vertical-menu-modern.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/weather-icons/climacons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/simple-line-icons/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/meteocons/style.css')}}">
    @stack('end-styles')
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <style>
        .table th, .table td {
            padding: 0.75rem;
        }
    </style>
    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
</head>
<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar"
      data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <div id="app">
        @include('layouts.components.top-nav')
        @include('layouts.components.sidebar-menu')
        <main class="">
            <div class="app-content content">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>

        </main>
        <footer class="footer footer-static footer-light navbar-border fixed-bottom">
            <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
                <span class="float-md-left d-block d-md-inline-block">Copyright © 2018. Developed by
                    <a class="text-bold-800 grey darken-2" href="http://makeduconsult.com" target="_blank">
                        Makedu Consult </a>. All rights reserved.
                </span>
            </p>
        </footer>
    </div>
    @stack('side-drawer')
    <!-- BEGIN VENDOR JS-->
    <script src="{{asset('vendors/js/vendors.min.js')}}" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    @stack('vendor-script')
    <!-- BEGIN PAGE VENDOR JS-->
    <script type="text/javascript" src="{{asset('vendors/js/ui/prism.min.js')}}"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN STACK JS-->
    <script src="{{asset('js/core/app-menu.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/core/app.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/scripts/customizer.js')}}" type="text/javascript"></script>
    @stack('end-script')
    @if(session()->has('notifications'))
        @component('layouts.components.toast-notification')
        @endcomponent
    @endif
    {{--<script src="{{asset('js/scripts.js')}}" type="text/javascript"></script>--}}
</body>
</html>
