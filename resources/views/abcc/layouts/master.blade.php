<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="Smart, Simple, Intuitive Online Invoicing">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@section('title'):: {{ config('app.name', 'aBillander') }} @show </title>

        <!-- Styles -->
        <link rel="shortcut icon" href="{{ asset('abcc_icon.png') }}" type="image/x-icon">
        <!-- link href='//fonts.googleapis.com/css?family=Roboto:300,400,700,900,100' rel='stylesheet' type='text/css' -->

        <link href="{{ asset('assets/theme/css/bootstrap-united.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/extra-buttons.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
        @yield('styles')
    </head>
    <body>
        <div id="app">
            @include('abcc/layouts/nav')
            <div class="container-fluid" style="margin: 10px 0px 10px 0px;"> 
                @include('abcc/layouts/notifications')
                @yield('content')

    {{-- --}}

            @auth('customer')
                @include('abcc/layouts/modal_feedback')
            @endif
                @include('layouts/modal_about')

    {{-- --}}

                @yield('modals')
           </div>
            @include('abcc/layouts/footer')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/plugins/jQuery/jquery.min.js' ) }}" type="text/javascript"></script>
          <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
        <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

        <!-- script src="{{ asset('js/base.js'  ) }}" type="text/javascript"></script>
        <script src="{{ asset('js/common.js') }}" type="text/javascript"></script -->

        <script src="{{ asset('assets/theme/js/common.js') }}" type="text/javascript"></script>

{{--
       <script type="text/javascript">

           // Passing data from Eloquent to Javascript
           // See: https://github.com/laracasts/PHP-Vars-To-Js-Transformer

           var PRICES_ENTERED_WITH_TAX = {!! \App\Configuration::get('PRICES_ENTERED_WITH_TAX') !!};

       </script>
--}}       

        @yield('scripts')
    </body>
</html>