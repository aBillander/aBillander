<!DOCTYPE html>
<html lang="{{ AbiContext::getContext()->language->iso_code }}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

	<title>{{ l('Production Sheet') }}</title>

    <!-- Bootstrap core CSS -->
    <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" -->

    <!-- link href="{{ asset('assets/theme/css/bootstrap-united.min.css') }}" rel="stylesheet" type="text/css"/ -->
    <!-- link href="{{ asset('assets/theme/css/extra-buttons.css') }}" rel="stylesheet" type="text/css"/ -->
    <!-- link href="{{ asset('assets/theme/css/custom.css') }}" rel="stylesheet" type="text/css"/ -->
    <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>

    <style>

        @yield('styles')
        
    </style>

</head>
<body class="login-page" style="background: white">

        @yield('content')

        @yield('scripts')

</body>
</html>