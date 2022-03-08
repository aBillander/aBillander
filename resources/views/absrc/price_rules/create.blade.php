@extends('layouts.master')

@section('title') {{ l('Price Rules - Create') }} @parent @endsection


@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('New Price Rule') }}</h3></div>
            <div class="panel-body" id="price_rule">

                @include('errors.list')

				{!! Form::open(array('route' => 'pricerules.store')) !!}
    
<div class="row">
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_from') ? 'has-error' : '' }}">
        {!! Form::label('date_from_form', l('Date from')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
               {!! $errors->first('date_from', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_to') ? 'has-error' : '' }}">
        {!! Form::label('date_to_form', l('Date to')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
               {!! $errors->first('date_to', '<span class="help-block">:message</span>') !!}
    </div>
</div>

    
<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('autocustomer_name', l('Customer Name')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('autocustomer_name', old('autocustomer_name'), array('class' => 'form-control', 'id' => 'autocustomer_name', 'onclick' => 'this.select()')) !!}

        {!! Form::hidden('customer_id', old('customer_id'), array('id' => 'customer_id')) !!}

        {!! $errors->first('customer_id', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('reference', l('Product Reference')) !!}
        {!! Form::text('reference', null, array('id' => 'reference', 'class' => 'form-control', 'onfocus' => 'this.blur()')) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('product_query', l('Product Name')) !!}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-content="{{ l('Search by Product Reference or Name') }}">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a>

        {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}

        {!! Form::text('product_query', null, array('id' => 'product_query', 'autocomplete' => 'off', 'class' => 'form-control', 'onclick' => 'this.select()')) !!}
    </div>

</div>

<div class="row">
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('from_quantity', l('From Quantity')) !!}
        {!! Form::text('from_quantity', old('from_quantity', 1), array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('price', l('Price')) !!}
        {!! Form::text('price', null, array('id' => 'price', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('currency_id', l('Currency')) !!}
        {!! Form::select('currency_id', ['' => l('-- All --', 'layouts')] + $currencyList, null, array('class' => 'form-control', 'id' => 'currency_id')) !!}
        {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
    </div>
</div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('pricerules') }}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-success" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>

				{!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')    @parent


{{-- Auto Complete --}}


<script type="text/javascript">

    $(document).ready(function() {

        // To get focus;
        $("#autocustomer_name").focus();

        $("#autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route('customerinvoices.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_fiscal + "</div>" )
                .appendTo( ul );
            };


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
                    var str = '[' + response.identification+'] ' + response.name_fiscal;
                    var shipping_method_id;

                    $("#autocustomer_name").val(str);
                    $('#customer_id').val(response.id);
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

                    $('#sales_rep_id').val(response.sales_rep_id);

                    console.log(response);
                }
            });
        }

    });

</script> 



{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
</script>


{{-- AutoComplete :: https://jqueryui.com/autocomplete/--}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

// http://stackoverflow.com/questions/21627170/laravel-tokenmismatchexception-in-ajax-request
// var _globalObj = {{ json_encode(array('_token'=> csrf_token())) }};

  $(function() {
    $( "#product_query" ).autocomplete({
      source: "{{ route('products.ajax.nameLookup') }}",
      minLength: 1,
      appendTo : "#price_rule",
      select: function( event, ui ) {
      //  alert( ui.item ?
      //    "Selected: " + ui.item.value + " aka " + ui.item.id :
      //    "Nothing selected, input was " + this.value );

        $( "#product_query" ).val(ui.item.name);
        $( "#reference" ).val( ui.item.reference );
        $( "#product_id" ).val( ui.item.id );

        // Product has combinations?
        $("#product_options").addClass('loading');

           var token = "{{ csrf_token() }}";        // See also: http://words.weareloring.com/development/laravel/laravel-4-csrf-tokens-when-using-jquerys-ajax/
           $.ajax({
              type: 'POST',
              url: "{{ route('products.ajax.optionsLookup') }}",
              dataType: 'html',
              data: "product_id="+ui.item.id+"&_token="+token,
              success: function(data) {
                 $("#product_options").html(data);
                 $("#product_options").removeClass('loading');

                 if ( data == '' ) 
                 {
                      $( "#div_product_options" ).hide();
                      $( "#quantity" ).focus();
                 } else {
                      $( "#div_product_options" ).show();
                      $( "#product_query" ).blur();
                      // ...and/or set focus on first select field
                 }
              }
           });
          
          return false;
      }
    })
    // http://stackoverflow.com/questions/9887032/how-to-highlight-input-words-in-autocomplete-jquery-ui
    .data("ui-autocomplete")._renderItem = function (ul, item) {
//        var newText = String(item.value).replace(
//                new RegExp(this.term, "gi"),
//                "<span class='ui-state-highlight' style='color: #dd4814;'><strong>$&</strong></span>");

        return $("<li></li>")
//            .data("item.autocomplete", item)
//            .append("<a>" + newText + "</a>")
            .append( '<div>[' + item.id + '] [' + item.reference + '] ' + item.name + "</div>" )
            .appendTo(ul);
    };
  });
</script>

@endsection

@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">


<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

@endsection