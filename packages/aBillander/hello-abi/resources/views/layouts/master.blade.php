<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@section('title'):: {{ config('app.name', 'Laravel') }} @show </title>

        <!-- Styles -->
        <link rel="shortcut icon" href="{{ asset('company_icon.png') }}" type="image/x-icon">

        <link href="{{ asset('assets/theme/css/bootstrap-united.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/extra-buttons.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>

        @stack('styles')
    </head>
    <body>
        <div id="app">
            @yield('content')

            @include('layouts.footer')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/plugins/jQuery/jquery.min.js' ) }}" type="text/javascript"></script>
        <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/common.js') }}" type="text/javascript"></script>

        @stack('scripts')
    </body>
</html>
