{{--
@inject('request', 'Illuminate\Http\Request')

@if($request->segment(2) == 'pos' && ($request->segment(3) == 'interface' || $request->segment(4) == 'edit'))
    @php
        $pos_layout = true;
    @endphp
@else
    @php
        $pos_layout = false;
    @endphp
@endif
--}}

@php
    $pos_layout = $pos_layout ?? false;
@endphp

@php
    $whitelist = ['127.0.0.1', '::1'];
@endphp

<!DOCTYPE html>
<html lang="{{ AbiContext::getContext()->language->iso_code }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@section('title'):: {{ config('app.name', 'aBillander') }} @show </title>
        
        <!-- Styles -->
        <link rel="shortcut icon" href="{{ asset('assets/theme/company_pos_icon.png') }}" type="image/x-icon">
        
        @include('pos::layouts.partials.css')

        @yield('css')
    </head>

    <body class="@if($pos_layout) hold-transition lockscreen @else hold-transition skin-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'blue-light'}}@endif sidebar-mini @endif">
        <div class="wrapper thetop">
            <script type="text/javascript">
                if(localStorage.getItem("upos_sidebar_collapse") == 'true'){
                    var body = document.getElementsByTagName("body")[0];
                    body.className += " sidebar-collapse";
                }
            </script>
            @if(!$pos_layout)
                @include('pos::layouts.partials.header')
                @include('pos::layouts.partials.sidebar')
            @else
                @include('pos::layouts.partials.header-pos')
            @endif

            @if(in_array($_SERVER['REMOTE_ADDR'], $whitelist))
                <input type="hidden" id="__is_localhost" value="true">
            @endif

            <!-- Content Wrapper. Contains page content -->
            <div class="@if(!$pos_layout) content-wrapper @endif">
                <!-- empty div for vuejs -->
                <div id="app">
                    @yield('vue')
                </div>
{{--
                <!-- Add currency related field-->
                <input type="hidden" id="__code" value="{{session('currency')['code']}}">
                <input type="hidden" id="__symbol" value="{{session('currency')['symbol']}}">
                <input type="hidden" id="__thousand" value="{{session('currency')['thousand_separator']}}">
                <input type="hidden" id="__decimal" value="{{session('currency')['decimal_separator']}}">
                <input type="hidden" id="__symbol_placement" value="{{session('business.currency_symbol_placement')}}">
                <input type="hidden" id="__precision" value="{{session('business.currency_precision', 2)}}">
                <input type="hidden" id="__quantity_precision" value="{{session('business.quantity_precision', 2)}}">
                <!-- End of currency related field-->
--}}
                
                    <input type="hidden" id="view_export_buttons">
                
{{--
                @if(isMobile())
                    <input type="hidden" id="__is_mobile">
                @endif
--}}
                @if (session('status'))
                    <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
                @endif
                @yield('content')

                <div class='scrolltop no-print'>
                    <div class='scroll icon'><i class="fas fa-angle-up"></i></div>
                </div>

                <!-- This will be printed -->
                <section class="invoice print_section" id="receipt_section">
                </section>
                
            </div>
            @include('pos::home.todays_profit_modal')
            <!-- /.content-wrapper -->

            @if(!$pos_layout)
                @include('pos::layouts.partials.footer')
            @else
                @include('pos::layouts.partials.footer_pos')
            @endif

            <audio id="success-audio">
              <source src="{{ asset('assets/abi-pos/audio/success.ogg?v=' . $asset_v) }}" type="audio/ogg">
              <source src="{{ asset('assets/abi-pos/audio/success.mp3?v=' . $asset_v) }}" type="audio/mpeg">
            </audio>
            <audio id="error-audio">
              <source src="{{ asset('assets/abi-pos/audio/error.ogg?v=' . $asset_v) }}" type="audio/ogg">
              <source src="{{ asset('assets/abi-pos/audio/error.mp3?v=' . $asset_v) }}" type="audio/mpeg">
            </audio>
            <audio id="warning-audio">
              <source src="{{ asset('assets/abi-pos/audio/warning.ogg?v=' . $asset_v) }}" type="audio/ogg">
              <source src="{{ asset('assets/abi-pos/audio/warning.mp3?v=' . $asset_v) }}" type="audio/mpeg">
            </audio>
        </div>

        @if(!empty($__additional_html))
            {!! $__additional_html !!}
        @endif

        @include('pos::layouts.partials.javascripts')

        <div class="modal fade view_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel"></div>

        @if(!empty($__additional_views) && is_array($__additional_views))
            @foreach($__additional_views as $additional_view)
                @includeIf($additional_view)
            @endforeach
        @endif
    </body>

</html>