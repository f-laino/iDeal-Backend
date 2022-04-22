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
    <link href="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css')  }}" rel="stylesheet">
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/perfect-scrollbar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    @stack('stylesheets')
</head>
<body class="menu-position-side menu-side-left full-screen">
<div class="all-wrapper solid-bg-all">

    @include('layouts.topbar')
    <div class="layout-w">
        @include('layouts.navbar')
        <div class="content-w">
            <div class="content-i">
                @isset($breadcrumbs)
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('home')}}">
                                HOME</a>
                        </li>
                        @foreach($breadcrumbs as $breadcrumbsTitle => $breadcrumbsTarget)
                            @php
                                if(!empty($breadcrumbsParams) && !empty($breadcrumbsParams[$breadcrumbsTitle]))
                                   $route = route("$breadcrumbsTarget", [$breadcrumbsParams[$breadcrumbsTitle]]);
                                else
                                    $route = route("$breadcrumbsTarget");
                            @endphp
                            <li class="breadcrumb-item">
                                <a href="{{ $route }}"> {{ $breadcrumbsTitle }} </a>
                            </li>
                        @endforeach
                    </ul>
                @endisset
                <div class="content-box">
                    <div class="element-wrapper compact pt-4">
                        @isset($addRoute)
                            <div class="element-actions">
                                <a class="btn btn-primary btn-sm" href="{{ route($addRoute) }}">
                                    <i class="os-icon os-icon-ui-22"></i>
                                    <span> Aggiungi </span>
                                </a>
                            </div>
                        @endisset
                        @isset($title)
                            <div class="element-header">
                                <h5 style="display:inline;"> {{ $title }} </h5>
                                @isset($total)
                                    <span class="sub-value">Totale elementi:</span>
                                    <span class="badge badge-primary"> {{ $total }}</span>
                                @endisset
                            </div>
                        @endisset
                            @include('partials.status')
                        @yield("app-content")
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::to('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.1/js/perfect-scrollbar.jquery.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.1/js/perfect-scrollbar.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::to('//s3.amazonaws.com/dynatable-docs-assets/js/jquery.dynatable.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::to('https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js') }}"></script>

<script src="{{ asset("js/cms-main.js") }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>



<script type="application/javascript">
    jQuery(document).ready(function ($) {
        $('.alert').fadeIn(800);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('*[data-href]').on('click', function () {
            window.location = $(this).data("href");
        });
    });
</script>
@stack("scripts")
</body>
</html>
