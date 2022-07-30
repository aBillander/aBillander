<script type="text/javascript">
    base_path = "{{url('/')}}";
    //used for push notification
    APP = {};
    APP.PUSHER_APP_KEY = '{{config('broadcasting.connections.pusher.key')}}';
    APP.PUSHER_APP_CLUSTER = '{{config('broadcasting.connections.pusher.options.cluster')}}';
    APP.INVOICE_SCHEME_SEPARATOR = '{{config('constants.invoice_scheme_separator')}}';
    //variable from app service provider
    APP.PUSHER_ENABLED = '{{$__is_pusher_enabled}}';
    @auth
        @php
            $user = Auth::user();
        @endphp
        APP.USER_ID = "{{$user->id}}";
    @else
        APP.USER_ID = '';
    @endauth
</script>

<script src="{{ asset('assets/abi-pos/js/vendor.js?v=' . $asset_v) }}"></script>

@if(file_exists(public_path('assets/abi-pos/js/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    <script src="{{ asset('assets/abi-pos/js/lang/' . session()->get('user.language', config('app.locale') ) . '.js?v=' . $asset_v) }}"></script>
@else
    <script src="{{ asset('assets/abi-pos/js/lang/en.js?v=' . $asset_v) }}"></script>
@endif
@php
    $business_date_format = session('business.date_format', config('constants.default_date_format'));
    $datepicker_date_format = str_replace('d', 'dd', $business_date_format);
    $datepicker_date_format = str_replace('m', 'mm', $datepicker_date_format);
    $datepicker_date_format = str_replace('Y', 'yyyy', $datepicker_date_format);

    $moment_date_format = str_replace('d', 'DD', $business_date_format);
    $moment_date_format = str_replace('m', 'MM', $moment_date_format);
    $moment_date_format = str_replace('Y', 'YYYY', $moment_date_format);

    $business_time_format = session('business.time_format');
    $moment_time_format = 'HH:mm';
    if($business_time_format == 12){
        $moment_time_format = 'hh:mm A';
    }

    $common_settings = !empty(session('business.common_settings')) ? session('business.common_settings') : [];

    $default_datatable_page_entries = !empty($common_settings['default_datatable_page_entries']) ? $common_settings['default_datatable_page_entries'] : 25;
@endphp

<script>
    Dropzone.autoDiscover = false;
    moment.tz.setDefault('{{ Session::get("business.time_zone") }}');
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        @if(config('app.debug') == false)
            $.fn.dataTable.ext.errMode = 'throw';
        @endif
    });
    
    var financial_year = {
        start: moment('{{ Session::get("financial_year.start") }}'),
        end: moment('{{ Session::get("financial_year.end") }}'),
    }
    @if(file_exists(public_path('AdminLTE/plugins/select2/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    //Default setting for select2
    $.fn.select2.defaults.set("language", "{{session()->get('user.language', config('app.locale'))}}");
    @endif

    var datepicker_date_format = "{{$datepicker_date_format}}";
    var moment_date_format = "{{$moment_date_format}}";
    var moment_time_format = "{{$moment_time_format}}";

    var app_locale = "{{session()->get('user.language', config('app.locale'))}}";

    var non_utf8_languages = [
{{--
        @foreach(config('constants.non_utf8_languages') as $const)
        "{{$const}}",
        @endforeach
--}}
    ];

    var __default_datatable_page_entries = "{{$default_datatable_page_entries}}";

    var __new_notification_count_interval = "{{config('constants.new_notification_count_interval', 60)}}000";
</script>

{{--
@if(file_exists(public_path('js/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    <script src="{{ asset('js/lang/' . session()->get('user.language', config('app.locale') ) . '.js?v=' . $asset_v) }}"></script>
@else
    <script src="{{ asset('js/lang/en.js?v=' . $asset_v) }}"></script>
@endif
--}}

<script src="{{ asset('assets/abi-pos/js/functions.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/abi-pos/js/common.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/abi-pos/js/app.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/abi-pos/js/documents_and_note.js?v=' . $asset_v) }}"></script>



@if(!empty($__system_settings['additional_js']))
    {!! $__system_settings['additional_js'] !!}
@endif

@yield('javascript')

{{--
@if(Module::has('Essentials'))
  @includeIf('essentials::layouts.partials.footer_part')
@endif
--}}

<script type="text/javascript">
    $(document).ready( function(){
        var locale = "{{session()->get('user.language', config('app.locale'))}}";
        var isRTL = false;      {{-- @if(in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl'))) true; @else false; @endif --}}

        $('#calendar').fullCalendar('option', {
            locale: locale,
            isRTL: isRTL
        });
    });
</script>