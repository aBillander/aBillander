@extends('layouts.master')

@section('title') {{ l('Commission Settlements - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
                <h3 class="panel-title">{{ l('New Commission Settlement') }}
                    @if ( $salesrep ) 
                        :: &nbsp; <strong>{{ $salesrep->name }}</strong> <span class="btn btn-xs btn-grey" title="{{l('Commission Percent')}}">{{ $salesrep->as_percent( 'commission_percent' ) }}%</span>
                    @endif
                </h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'commissionsettlements.store', 'id' => 'commission_settlements_form')) !!}

					@include('commission_settlements._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts') @parent 


{{-- Auto Complete --}}

<!-- script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script -->


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>

    $(document).ready(function() {

{{--
        $("#autocustomer_name").val('');

        // To get focus;
        // $("#autocustomer_name").focus();

        $("#autocustomer_name").autocomplete({
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


        $("#direct_debits_form").on("submit", function(){
           //Code: 
           if ( $("#autocustomer_name").val().trim() == '' )
              $('#customer_id').val('');

           return true;
         });
--}}
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

                    $("#autocustomer_name").val(str);
                    $('#customer_id').val(response.id);
/*
                    if (response.sales_equalization > 0) {
                        $('#sales_equalization').show();
                    } else {
                        $('#sales_equalization').hide();
                    }

    //                $('#sequence_id').val(response.work_center_id);
                    $('#document_date_form').val('{{ abi_date_form_short( 'now' ) }}');
                    $('#delivery_date_form').val('');

                    if ( response.payment_method_id > 0 ) {
                      $('#payment_method_id').val(response.payment_method_id);
                    } else {
                      $('#payment_method_id').val({{ intval(AbiConfiguration::get('DEF_CUSTOMER_PAYMENT_METHOD'))}});
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

                    $('#warehouse_id').val({{ intval(AbiConfiguration::get('DEF_WAREHOUSE'))}});

                    shipping_method_id = response.shipping_method_id;
                    if (shipping_method_id == null) {
                        shipping_method_id = "{{ intval(AbiConfiguration::get('DEF_SHIPPING_METHOD'))}}";
                    }
                    $('#shipping_method_id').val( shipping_method_id );

                    $('#sales_rep_id').val(response.sales_rep_id);
*/
                    console.log(response);
                }
            });
        }



  $(function() {
    $( "#document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $('#document_date_form').val("{{ old('document_date_form', abi_date_form_short( 'now' )) }}");
  
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });


  $(function() {
    $( "#document_due_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection




@section('styles') @parent

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection
