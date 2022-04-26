@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="priceruleModal" tabindex="-1" role="dialog" aria-labelledby="priceruleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="priceruleModalLabel">{{ l('Create Price Rule') }} :: [{{ $product->reference }}] {{ $product->name }}</h4>
      </div>
      <div class="modal-body" id="price_rule">
          {!! Form::hidden('product_id', $product->id, array('id' => 'product_id')) !!}

    
<div class="row">
</div>

    
<div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('autocustomer_name', l('Customer Name', 'pricerules')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).', 'pricerules') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>

        {!! Form::text('autocustomer_name', null, array('id' => 'autocustomer_name', 'autocomplete' => 'off', 'class' => 'form-control', 'onclick' => 'this.select()')) !!}

        {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}

        {!! $errors->first('customer_id', '<span class="help-block">:message</span>') !!}
    </div>

</div>

<div class="row">
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('from_quantity', l('From Quantity', 'pricerules')) !!}
        {!! Form::text('from_quantity', old('from_quantity', 1), array('class' => 'form-control', 'onclick' => 'this.select()')) !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('rule_price', l('Price', 'pricerules')) !!}
        {!! Form::text('rule_price', null, array('id' => 'rule_price', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('rule_currency_id', l('Currency')) !!}
        {!! Form::select('rule_currency_id', ['' => l('-- All --', 'layouts')] + $currencyList, null, array('class' => 'form-control', 'id' => 'rule_currency_id')) !!}
        {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_from') ? 'has-error' : '' }}">
        {!! Form::label('date_from_form', l('Date from', 'pricerules')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control', 'id' => 'date_from_form')) !!}
               {!! $errors->first('date_from', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_to') ? 'has-error' : '' }}">
        {!! Form::label('date_to_form', l('Date to', 'pricerules')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control', 'id' => 'date_to_form')) !!}
               {!! $errors->first('date_to', '<span class="help-block">:message</span>') !!}
    </div>
</div>



      </div>

      <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_priceruleSubmit" id="modal_priceruleSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Save', [], 'layouts')}}</button>

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

    });

</script> 



{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
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
</script>


{{-- AutoComplete :: https://jqueryui.com/autocomplete/--}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>


  $(function() {


        $("#autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route('customerinvoices.ajax.customerLookup') }}",
            minLength : 1,
            appendTo : "#priceruleModal",

            select : function(key, value) {

                getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_fiscal + "</div>" )
                .appendTo( ul );
            };


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


{{-- *************************************** --}}



          $("body").on('click', "#modal_priceruleSubmit", function() {

            var url = "{{ route('pricerules.store') }}";
            var token = "{{ csrf_token() }}";

/*      
category_id : ,
product_id : ,
combination_id : ,
customer_id : ,
customer_group_id : ,
currency_id : ,
rule_type : ,
price : ,
discount_type : ,
discount_percent : ,
discount_amount : ,
discount_amount_is_tax_incl : ,
from_quantity : ,
date_from_form : ,
date_to_form : ,
*/

            var payload = {
                              product_id : $('#product_id').val(),

                              customer_id : $('#customer_id').val(),
                              currency_id : $('#rule_currency_id').val(),

                              xrule_type : "price",
                              price : $('#rule_price').val(),
                              from_quantity : $('#from_quantity').val(),

                              date_from_form : $('#date_from_form').val(),
                              date_to_form : $('#date_to_form').val()
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    
                    getProductPriceRules();

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#priceruleModal').modal('toggle');

            // $('#priceruleModal').modal({show: true});

                    showAlertDivWithDelay("#msg-pricerule-success");
                }
            });

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

<style>

.modal-content {
  overflow:hidden;
}

/*
See: https://coreui.io/docs/components/buttons/ :: Brand buttons
*/
.btn-behance {
    color: #fff;
    background-color: #1769ff;
    border-color: #1769ff;
}

</style>

@endsection




