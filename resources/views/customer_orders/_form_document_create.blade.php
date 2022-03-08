
               <div class="panel-body">

      <div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('customer_id') ? 'has-error' : '' }}">
            {{ l('Customer Name') }} 
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
            <span id="sales_equalization" class="label label-info" style="display: none;"> {{l('Equalization Tax')}} </span>
            {!! Form::text('document_autocustomer_name', old('document_autocustomer_name'), array('class' => 'form-control', 'id' => 'document_autocustomer_name')) !!}

            {!! Form::hidden('customer_id', old('customer_id'), array('id' => 'customer_id')) !!}
            {!! Form::hidden('invoicing_address_id', old('invoicing_address_id'), array('id' => 'invoicing_address_id')) !!}

            {!! $errors->first('customer_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('sequence_id', $sequenceList, old('sequence_id'), array('class' => 'form-control', 'id' => 'sequence_id')) !!}
            {!! $errors->first('sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
            {{ l('Reference / Project') }}
            {!! Form::text('reference', old('reference'), array('class' => 'form-control', 'id' => 'reference')) !!}
            {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('document_date') ? 'has-error' : '' }}">
               {{ l('Document Date') }}
               {!! Form::text('document_date_form', old('document_date_form'), array('class' => 'form-control', 'id' => 'document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('document_date', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="col-lg-2 col-md-2 col-sm-2 {{ $errors->has('delivery_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Delivery Date') }}
               {!! Form::text('delivery_date_form', old('delivery_date_form'), array('class' => 'form-control', 'id' => 'delivery_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('delivery_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

      <!-- /div>
      <div class="row" -->

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('payment_method_id') ? 'has-error' : '' }}">
            {{ l('Payment Method') }}
            {!! Form::select('payment_method_id', array('' => l('-- Please, select --', [], 'layouts')) + $payment_methodList, old('payment_method_id'), array('class' => 'form-control', 'id' => 'payment_method_id')) !!}
            {!! $errors->first('payment_method_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
            {{ l('Currency') }}
            {!! Form::select('currency_id', $currencyList, old('currency_id'), array('class' => 'form-control', 'id' => 'currency_id', 'onchange' => 'get_currency_rate($("#currency_id").val())')) !!}
            {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_conversion_rate') ? 'has-error' : '' }}">

            {{ l('Conversion Rate') }}
            <div  class="input-group">
              {!! Form::text('currency_conversion_rate', old('currency_conversion_rate'), array('class' => 'form-control', 'id' => 'currency_conversion_rate')) !!}
              {!! $errors->first('currency_conversion_rate', '<span class="help-block">:message</span>') !!}

              <span class="input-group-btn" title="{{ l('Update Conversion Rate') }}">
              <button class="btn btn-md btn-lightblue" type="button" onclick="get_currency_rate($('#currency_id').val());">
                  <span class="fa fa-money"></span>
              </button>
              </span>
            </div>

         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('sales_rep_id') ? 'has-error' : '' }}">
            {{ l('Sales Representative') }}
            {!! Form::select('sales_rep_id', array('0' => l('-- Please, select --', [], 'layouts')) + $salesrepList, old('sales_rep_id'), array('class' => 'form-control', 'id' => 'sales_rep_id')) !!}
            {!! $errors->first('sales_rep_id', '<span class="help-block">:message</span>') !!}
         </div>
{{--
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('down_payment') ? 'has-error' : '' }}">
            {{ l('Down Payment') }}
            {!! Form::text('down_payment', old('down_payment'), array('class' => 'form-control', 'id' => 'down_payment')) !!}
            {!! $errors->first('down_payment', '<span class="help-block">:message</span>') !!}
         </div>
--}}
      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            {!! Form::select('shipping_address_id', [], old('shipping_address_id'), array('class' => 'form-control', 'id' => 'shipping_address_id')) !!}
            {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            {{ l('Warehouse') }}
            {!! Form::select('warehouse_id', ['0' => l('-- Please, select --', [], 'layouts')] + $warehouseList, old('warehouse_id'), array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipping_method_id') ? 'has-error' : '' }}">
            {{ l('Shipping Method') }}
            {!! Form::select('shipping_method_id', ['0' => l('-- Please, select --', [], 'layouts')] + $shipping_methodList, old('shipping_method_id'), array('class' => 'form-control', 'id' => 'shipping_method_id')) !!}
            {!! $errors->first('shipping_method_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_conditions') ? 'has-error' : '' }}">
            {{ l('Shipping Conditions') }}
            {!! Form::textarea('shipping_conditions', old('shipping_conditions'), array('class' => 'form-control', 'id' => 'shipping_conditions', 'rows' => '1')) !!}
            {!! $errors->first('shipping_conditions', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('notes') ? 'has-error' : '' }}" xstyle="margin-top: 20px;">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', old('notes'), array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('notes_to_customer') ? 'has-error' : '' }}">
            {{ l('Notes to Customer') }}
            {!! Form::textarea('notes_to_customer', old('notes_to_customer'), array('class' => 'form-control', 'id' => 'notes_to_customer', 'rows' => '2')) !!}
            {{ $errors->first('notes_to_customer', '<span class="help-block">:message</span>') }}
         </div>

      </div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
                  <input type="hidden" id="nextAction" name="nextAction" value="" />
                  <button class="btn btn-info hidden" type="submit" onclick="this.disabled=true;$('#nextAction').val('saveAndConfirm');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Confirm', [], 'layouts')}}
                  </button>
               </div>


@section('styles')    @parent

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


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>


{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

        @if ( ($cid = intval( old('customer_id') )) > 0 ) 

              var id = {{ $cid }};
              var url = "{{ route($model_path.'.ajax.customer.AdressBookLookup', [$cid]) }}";
              
               $.get(url, function(result){
                    $('#shipping_address_id').empty();

                    $.each(result, function(index, element){
                      $('#shipping_address_id').append('<option value="'+ index +'">'+ element +'</option>');
                    });

                    $('#shipping_address_id').val( {{ old('shipping_address_id') }} );

//                    $("[data-toggle=popover]").popover();
               });
        @endif

        });



function get_currency_rate(currency_id)
{
    var pload = '';

    pload = pload + "currency_id="+currency_id;
    pload = pload + "&_token="+$('[name="_token"]').val();

   $.ajax({
      type: 'POST',
      url: '{{ route('currencies.ajax.rateLookup') }}',
      dataType: 'html',
      data: pload,
      success: function(data) {
         var theHTML = data;
         if ( theHTML == '' ) {
              theHTML = '<div class="alert alert-warning alert-block"><i class="fa fa-warning"></i> {{l('No records found', [], 'layouts')}}</div>';
         }
         $("#currency_conversion_rate").val(theHTML);
      }
   });
}

    </script> 

    <!-- script type="text/javascript">

        // Select default tax
        if ( !($('select[name="tax_id"]').val() > 0) ) {
          var def_taxID = {{ AbiConfiguration::get('DEF_TAX') }};

          $('select[name="tax_id"]').val(def_taxID);
        }

        // Select default warehouse
        if ( !($('select[name="warehouse_id"]').val() > 0) ) {
          var def_warehouseID = {{ AbiConfiguration::get('DEF_WAREHOUSE') }};

          $('select[name="warehouse_id"]').val(def_warehouseID);
        }

        // Select default decimals
        if ( {{ intval( !isset($product->quantity_decimal_places) ) }} ) {
          var def_decimalsID = {{ AbiConfiguration::get('DEF_QUANTITY_DECIMALS') }};

          $('select[name="quantity_decimal_places"]').val(def_decimalsID);
        }

    </script -->



{{-- Auto Complete --}}


<script type="text/javascript">

    $(document).ready(function() {
       
//        $("#order_autocustomer_name").val('');
// alert('{{ old('order_autocustomer_name') }}');

        // Cosmetic powder:
        get_currency_rate($('#currency_id').val());

        // To get focus;
        $("#document_autocustomer_name").focus();

        $("#document_autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route($model_path.'.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + (item.identification ? item.identification : '--')+'] ' + item.name_regular + ' [' + (item.reference_external ? item.reference_external : '--') +']' + "</div>" )
                .appendTo( ul );
            };


        function getCustomerData( customer_id )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route($model_path.'.ajax.customerLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    customer_id: customer_id
                },
                success: function (response) {
                    var str = '[' + response.identification+'] ' + response.name_fiscal + ' [' + response.reference_external +']';
                    var shipping_method_id;

                    $("#document_autocustomer_name").val(str);
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

                    console.log(response);
                }
            });
        }

        @if ($customer_id ?? 0)
            getCustomerData( {{ $customer_id }} );
            
            // To get focus;
            $("#sequence_id").focus();
        @endif

    });

</script> 


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#delivery_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection