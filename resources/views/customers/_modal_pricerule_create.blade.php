@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="priceruleModal" tabindex="-1" role="dialog" aria-labelledby="priceruleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="priceruleModalLabel">{{ l('Create Price Rule') }} :: {{ $customer->name_commercial }}</h4>
      </div>
      <div class="modal-body" id="price_rule">
          {{-- Form::hidden('customer_id', null, array('id' => 'customer_id')) --}}

    
<div class="row">
</div>

    
<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('reference', l('Reference')) !!}
        {!! Form::text('reference', null, array('id' => 'reference', 'class' => 'form-control', 'onfocus' => '$("#product_query").focus()')) !!}
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
        {!! Form::text('from_quantity', old('from_quantity', 1), array('class' => 'form-control', 'onclick' => 'this.select()')) !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('rule_price', l('Price')) !!}
        {!! Form::text('rule_price', null, array('id' => 'rule_price', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('rule_currency_id', l('Currency')) !!}
        {!! Form::select('rule_currency_id', ['' => l('-- All --', 'layouts')] + $currencyList, null, array('class' => 'form-control', 'id' => 'rule_currency_id')) !!}
        {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_from') ? 'has-error' : '' }}">
        {!! Form::label('date_from_form', l('Date from')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control', 'id' => 'date_from_form')) !!}
               {!! $errors->first('date_from', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date_to') ? 'has-error' : '' }}">
        {!! Form::label('date_to_form', l('Date to')) !!}
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
        $( "#product_query" ).focus();


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

// http://stackoverflow.com/questions/21627170/laravel-tokenmismatchexception-in-ajax-request
// var _globalObj = {{ json_encode(array('_token'=> csrf_token())) }};

  $(function() {
    $( "#product_query" ).autocomplete({
      source: "{{ route('products.ajax.nameLookup') }}",
      minLength: 1,
      appendTo : "#priceruleModal",
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
                    
                    getCustomerPriceRules();

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




