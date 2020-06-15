@extends('layouts.master')

@section('title') {{ l('Invoice Customer Shipping Slips') }} @parent @stop


@section('content')

<div class="row hide" id="cssload">
    <div class="col-md-12">

{{--
<div class="cssload-dots">
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
</div>

<svg version="1.1" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <filter id="goo">
      <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="12" ></feGaussianBlur>
      <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0 0 0 1 0 0 0 0 0 18 -7" result="goo" ></feColorMatrix>
      <!--<feBlend in2="goo" in="SourceGraphic" result="mix" ></feBlend>-->
    </filter>
  </defs>
</svg>
--}}

<div class="page-header">
    <h2>
         <a class="btn btn-sm alert-info" href="{{ route('customershippingslips.index') }}" title="{{l('Customer Shipping Slips')}}"><i class="fa fa-truck"></i></a> <span style="color: #cccccc;">/</span> {{ l('Invoice Customer Shipping Slips') }}

        <a href="" class="btn btn-success disabled" onclick="return false;" style="margin-left: 72px;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> {{ l('Processing...', 'layouts') }}</a>

    </h2>     
</div>

    </div>
</div> 



<div class="row" id="content-header">
    <div class="col-md-12">

<div class="page-header">
    <h2>
         <a class="btn btn-sm alert-info" href="{{ route('customershippingslips.index') }}" title="{{l('Customer Shipping Slips')}}"><i class="fa fa-truck"></i></a> <span style="color: #cccccc;">/</span> {{ l('Invoice Customer Shipping Slips') }}
    </h2>
</div>


<div class="container">
    <div class="row">

            <div xclass="col-lg-3 col-md-6">
            <div class="panel panel-danger">
              <div class="panel-heading" xstyle="color: #ffffff;
background-color: #772953;
border-color: #772953;">
                <h3 class="panel-title"><i class="fa fa-money"></i> {{ l('Customer Shipping Slips Filter') }}</h3>
              </div>


{!! Form::open(array('route' => 'customershippingslips.invoicer.process', 'id' => 'invoicer_form', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('invoicer_date_from_form', l('Date from', [], 'layouts')) !!}
        {!! Form::text('invoicer_date_from_form', null, array('id' => 'invoicer_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('invoicer_date_to_form', l('Date to', [], 'layouts')) !!}
        {!! Form::text('invoicer_date_to_form', null, array('id' => 'invoicer_date_to_form', 'class' => 'form-control')) !!}
    </div>

     <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('invoicer_autocustomer_name', 'Cliente') !!}
        {!! Form::text('invoicer_autocustomer_name', null, array('class' => 'form-control', 'id' => 'invoicer_autocustomer_name')) !!}

        {!! Form::hidden('invoicer_customer_id', null, array('id' => 'invoicer_customer_id')) !!}
     </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('invoicer_date_form', l('Invoice Date')) !!}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                              data-content="{{ l('This Date will appear on newly created Invoices') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
        {!! Form::text('invoicer_date_form', null, array('id' => 'invoicer_date_form', 'class' => 'form-control')) !!}
    </div>


    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('invoicer_status', l('Status')) !!}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                              data-content="{{ l('Invoices will be created with this Status') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
        {!! Form::select('invoicer_status', $statusList, null, array('class' => 'form-control')) !!}
    </div>

    </div>


    <div class="row">

    <div class="form-group col-lg-12 col-md-12 col-sm-12">
        <h4 class="list-group-item-heading text-primary" style="margin-top: 12px;">{{ l('Grouping Options') }}</h4>
    </div>

    </div>


    <div class="row">


<div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-invoicer_group_by_customer">
 {!! Form::label('invoicer_group_by_customer', l('By Customer'), ['class' => 'control-label']) !!}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" data-html="true"  
                              data-content="{{ l('Yes: One Invoice per Customer<br />No: One Invoice per Shipping Slip') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('invoicer_group_by_customer', '1', true, ['id' => 'invoicer_group_by_customer_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('invoicer_group_by_customer', '0', false, ['id' => 'invoicer_group_by_customer_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>



<div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-invoicer_group_by_shipping_address">
 {!! Form::label('invoicer_group_by_shipping_address', l('By Shipping Address'), ['class' => 'control-label']) !!}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                              data-content="{{ l('Yes: One Invoice per Shipping Address') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('invoicer_group_by_shipping_address', '1', false, ['id' => 'invoicer_group_by_shipping_address_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('invoicer_group_by_shipping_address', '0', true, ['id' => 'invoicer_group_by_shipping_address_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>

                  </div>
{{--
                  <div class="row">

                     <div class="form-group col-lg-12 text-center" xstyle="padding-top: 22px">
                          {!! Form::submit('Ver Listado', array('class' => 'btn btn-success')) !!}
                    </div>

                  </div>
--}}

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="loadingpage();this.disabled=false;this.form.submit();">
                     <i class="fa fa-refresh"></i>
                     &nbsp; {!! l('Process', [], 'layouts') !!}
                  </button>
               </div>

{!! Form::close() !!}

            </div>

            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->





    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->





    </div><!-- div class="row" ENDS -->

</div>


    </div>
</div> <!-- div class="row hide" id="cssload" ENDS -->




{{-- ********************************************************** --}}




{{-- ***************************************************** --}}


<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-2">
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; { { l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-9 col-md-9 col-sm-10">
      <div class="jumbotron" style="background: no-repeat url('{{URL::to('/assets/theme/images/Dashboard.jpg')}}'); background-size: 100% auto;min-height: 200px; margin-top: 40px;">


      </div>
      </div>

   </div>
</div>

@endsection


@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

          $('#invoicer_date_from_form').val( '' );
          $('#invoicer_date_to_form'  ).val( '' );
          $('#invoicer_date_form'  ).val( '{{ abi_date_short( \Carbon\Carbon::now() ) }}' );
          $('#invoicer_status'  ).val( 'draft' );


        $("#invoicer_autocustomer_name").val('');

        // To get focus;
        // $("#autocustomer_name").focus();

        $("#invoicer_autocustomer_name").autocomplete({
            source : "{{ route('customerinvoices.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_regular + "</div>" )
                .appendTo( ul );
            };


        $("#invoicer_form").on("submit", function(){
           //Code: 
           if ( $("#invoicer_autocustomer_name").val().trim() == '' )
              $('#invoicer_customer_id').val('');

           return true;
         });


        });


        function getCustomerData( customer_id )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('customerinvoices.ajax.customerLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    customer_id: customer_id
                },
                success: function (response) {
                    var str = '[' + response.identification+'] ' + response.name_regular;
                    var shipping_method_id;

                    $("#invoicer_autocustomer_name").val(str);
                    $('#invoicer_customer_id').val(response.id);
/*
                    if (response.invoicer_equalization > 0) {
                        $('#invoicer_equalization').show();
                    } else {
                        $('#invoicer_equalization').hide();
                    }

    //                $('#sequence_id').val(response.work_center_id);
                    $('#document_date_form').val('{{ abi_date_form_short( 'now' ) }}');
                    $('#delivery_date_form').val('');

                    if ( response.payment_method_id > 0 ) {
                      $('#payment_method_id').val(response.payment_method_id);
                    } else {
                      $('#payment_method_id').val({{ intval(\App\Configuration::get('DEF_CUSTOMER_PAYMENT_METHOD'))}});
                    }

                    $('#currency_id').val(response.currency_id);
                    $('#currency_conversion_rate').val(response.currency.conversion_rate);
                    $('#down_payment').val('0.0');

                    $('#invoicing_address_id').val(response.invoicing_address_id);

                    // https://www.youtube.com/watch?v=FHQh-IGT7KQ
                    $('#shipping_address_id').empty();

    //                $('#shipping_address_id').append('<option value="0" disable="true" selected="true">=== Select Address ===</option>');

                    $.each(response.addresses, function(index, element){
                      $('#shipping_address_id').append('<option value="'+ element.id +'">'+ element.alias +'</option>');
                    });

                    if ( response.shipping_address_id > 0 ) {
                      $('#shipping_address_id').val(response.shipping_address_id);
                    } else {
                      $('#shipping_address_id').val(response.invoicing_address_id);
                    }

                    $('#warehouse_id').val({{ intval(\App\Configuration::get('DEF_WAREHOUSE'))}});

                    shipping_method_id = response.shipping_method_id;
                    if (shipping_method_id == null) {
                        shipping_method_id = "{{ intval(\App\Configuration::get('DEF_SHIPPING_METHOD'))}}";
                    }
                    $('#shipping_method_id').val( shipping_method_id );

                    $('#invoicer_rep_id').val(response.invoicer_rep_id);
*/
                    console.log(response);
                }
            });
        }



    </script> 



{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#invoicer_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#invoicer_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#invoicer_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

</script>

{{-- Page loader --}}

<script type="text/javascript">

$(document).ready(function() {
   $("#cssload").hide();
});

function loadingpage()
{
    $("#content-header").hide('slow');
    $("#content-body").hide('slow');

   $("#cssload").hide();
   $("#cssload").removeClass('hide');
   $("#cssload").slideDown( "slow" );
}

</script>

@endsection
