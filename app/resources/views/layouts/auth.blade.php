<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/ico"
          href="{{URL::to('https://d260o8t6723rz8.cloudfront.net/carplanner/website/favicon.ico?201810221400')}}">

    <link href="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css') }}"
          rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/perfect-scrollbar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    @yield('stylesheets')
</head>
<body class="auth-wrapper">
<div class="all-wrapper menu-side with-pattern">
    <div class="auth-box-w wider">
        <div class="logo-w" style="padding: 10%!important;">
            <a href="{{route('login')}}">
                <img
                        alt="Carplanner Logo"
                        style="width: 15rem; text-align: center;"
                        src="{{ URL::to('https://static.ideal-rent.com/logos/main.png') }}"
                >
            </a>
        </div>
        @yield("app-content")
    </div>
</div>


<script src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js') }}"></script>
<script src="{{ URL::to('https://code.jquery.com/jquery-3.2.1.slim.min.js') }}"></script>
<script src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js') }}"></script>
<script src="{{ URL::to('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset("js/cms-main.js") }}"></script>

@yield("javascript")

@stack("scripts")
</body>
</html>
