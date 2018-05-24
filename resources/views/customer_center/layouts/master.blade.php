<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="Smart, Simple, Intuitive Online Invoicing">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@section('title'):: aBillander @show </title>

        <!-- Styles -->
        <link rel="shortcut icon" href="{{ asset('abcccon.png') }}" type="image/x-icon">
        <!-- link href='//fonts.googleapis.com/css?family=Roboto:300,400,700,900,100' rel='stylesheet' type='text/css' -->

        <link href="{{ asset('assets/theme/css/bootstrap-united.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/extra-buttons.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/theme/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
        @yield('styles')
    </head>
    <body>
        @include('customer_center/layouts/nav')
        <div class="container-fluid" style="margin: 10px 0px 10px 0px;"> 
            @include('customer_center/layouts/notifications')
            @yield('content')

{{--

            @include('layouts/modal_feedback')
            @include('layouts/modal_about')

--}}

            @yield('modals')
       </div>
        @include('customer_center/layouts/footer')

        <!-- Scripts -->
        <script src="{{ asset('assets/plugins/jQuery/jquery.min.js' ) }}" type="text/javascript"></script>
          <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
        <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

        <script src="{{ asset('js/base.js'  ) }}" type="text/javascript"></script>
        <script src="{{ asset('js/common.js') }}" type="text/javascript"></script>

        <script type="text/javascript">
        $(function(){
           $("#f_feedback").on('submit', function(e){
              e.preventDefault();
              $.post("{{ URL::to('contact') }}", $(this).serialize(), function(data){

                 if (data == 'ERROR') {
                    $("#error").addClass("alert alert-danger");
                    $("#error").html('<a class="close" data-dismiss="alert" href="#">×</a><li class="error">{{ l('There was an error. Your message could not be sent.', [], 'layouts') }}</li>');
                 } else {
                     // Reset form
                     $("#notes").val('');
                     // $("#email").val('');
                     // $("#name").val('');
                     // 
                     $("#modal-body").html('<div class="alert alert-success">{{ l('Your email has been sent!', [], 'layouts') }}</div>');
                     $("#modal-footer").html('<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">{{ l('Continue', [], 'layouts') }}</button>');
                 }
              });
           });
        });
        </script>
        @yield('scripts')
    </body>
</html>