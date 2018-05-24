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
        <link rel="shortcut icon" href="{{ asset('xtracon.png') }}" type="image/x-icon">

        <link href="{{ asset('assets/theme/css/bootstrap-united.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/extra-buttons.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
        @yield('styles')
    </head>
    <body>
        <div id="app">
            @include('layouts/nav')
            <div class="container-fluid" style="margin: 10px 0px 10px 0px;"> 
                @include('layouts/notifications')
                @yield('content')
                @include('layouts/modal_about')
                @yield('modals')
           </div>
            @include('layouts/footer')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/plugins/jQuery/jquery.min.js' ) }}" type="text/javascript"></script>
        <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
        <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

        <script src="{{ asset('js/common.js') }}" type="text/javascript"></script>

        <script type="text/javascript">

            // https://stackoverflow.com/questions/11832914/round-to-at-most-2-decimal-places-only-if-necessary
            // usage:var n = 1.7777;    n.round(2); // 1.78

            Number.prototype.round = function(places) {
              return +(Math.round(this + "e+" + places)  + "e-" + places);
            }

            // Numbers as string rounding. Groovy!!!
            String.prototype.round = function(places) {
              return +(Math.round(parseFloat(this) + "e+" + places)  + "e-" + places);
            }

            // Passing data from Eloquent to Javascript
            // See: https://github.com/laracasts/PHP-Vars-To-Js-Transformer

            var PRICES_ENTERED_WITH_TAX = {!! \App\Configuration::get('PRICES_ENTERED_WITH_TAX') !!};

        </script>

        @yield('scripts')
    </body>
</html>