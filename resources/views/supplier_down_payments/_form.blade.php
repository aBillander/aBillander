
<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('reference') ? 'has-error' : '' }}">
        {!! Form::label('reference', l('Reference')) !!}
        {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
        {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', l('Description')) !!}
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('due_date') ? 'has-error' : '' }}">
        {!! Form::label('due_date_form', l('Due Date')) !!}
        {!! Form::text('due_date_form', null, array('id' => 'due_date_form', 'class' => 'form-control')) !!}
        {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
    </div>

@if ( isset($cheque) && ($cheque->status == 'paid') )
@php
          $statusList = ['paid' => $statusList['paid']];
@endphp
@endif
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('status', l('Status', 'layouts')) !!}
    {!! Form::select('status', $statusList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-3 col-md-3 col-sm-3">
    {!! Form::label('payment_type_id', l('Payment Type')) !!}
    {!! Form::select('payment_type_id', $payment_typeList, null, array('class' => 'form-control')) !!}
</div>

</div>

<div class="row">

    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
       {!! Form::label('autosupplier_name', l('Supplier')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).', 'supplierorders') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('autosupplier_name', null, array('class' => 'form-control', 'id' => 'autosupplier_name')) !!}
        {!! $errors->first('supplier_id', '<span class="help-block">:message</span>') !!}

        {!! Form::hidden('supplier_id', null, array('id' => 'supplier_id')) !!}
    </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('bank_id') ? 'has-error' : '' }}">
            {!! Form::label('bank_id', l('Drawee Bank'), ['class' => 'control-label']) !!}
            {!! Form::select('bank_id', ['' => l('-- Please, select --', [], 'layouts')] + $bankList, null, array('class' => 'form-control', 'id' => 'bank_id')) !!}
            {!! $errors->first('bank_id', '<span class="help-block">:message</span>') !!}
         </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('amount') ? 'has-error' : '' }}">
    {!! Form::label('amount', l('Amount')) !!}
    {!! Form::text('amount', null, array('id' => 'amount', 'class' => 'form-control')) !!}
    {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
</div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
            {!! Form::label('currency_id', l('Currency'), ['class' => 'control-label']) !!}
            {!! Form::select('currency_id', $currencyList, old('currency_id', \App\Configuration::getInt('DEF_CURRENCY')), array('class' => 'form-control', 'id' => 'currency_id')) !!}
            {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>

<div class="row">
    <div class="form-group col-lg-7 col-md-7 col-sm-7 {{ $errors->has('notes') ? 'has-error' : '' }}">
       {!! Form::label('notes', l('Notes', [], 'layouts')) !!}
       {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
       {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
    </div>
</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('supplier.downpayments.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}




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
  .ui-datepicker{ z-index: 9999 !important;}


/* Undeliver dropdown effect */
   .hover-item:hover {
      background-color: #d3d3d3 !important;
    }
</style>

@endsection


@section('scripts')    @parent


{{-- Auto Complete --}}
{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script type="text/javascript">

    $(document).ready(function() {

      @if ( $cheque ?? null )
        getSupplierData( {{ $cheque->supplier->id }}, {{ $cheque->bank_id }} );

        $("#autosupplier_name").val('{{ $cheque->supplier->name_regular }}');
        $("#supplier_id").val('{{ $cheque->supplier->id }}');
        // $("#bank_id").val('{{ $cheque->bank_id }}');
      @else
        $("#autosupplier_name").val('');
        $("#supplier_id").val('');
      @endif

        // To get focus;
        // $("#autosupplier_name").focus();

        $("#autosupplier_name").autocomplete({
//            source : "{{ route('suppliers.ajax.nameLookup') }}",
            source : "{{ route('supplierorders.ajax.supplierLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {
                // var str = '[' + value.item.identification+'] ' + value.item.name_regular + ' [' + value.item.reference_external +']';
                var str = value.item.name_regular + ' [' + value.item.reference_external +']';

                // ( value.item.id );

                $("#autosupplier_name").val(str);
                $('#supplier_id').val(value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + (item.identification ? item.identification : '--')+'] ' + item.name_regular + ' [' + (item.reference_external ? item.reference_external : '--') +']' + "</div>" )
                .appendTo( ul );
            };


        function getSupplierData( supplier_id, bank_id = 0 )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('supplierorders.ajax.supplierLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    supplier_id: supplier_id
                },
                success: function (response) {
                    var str = '[' + response.identification+'] ' + response.name_fiscal + ' [' + response.reference_external +']';
                    var shipping_method_id;

                    $("#document_autosupplier_name").val(str);
                    $('#supplier_id').val(response.id);
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
                      $('#payment_method_id').val({{ intval(\App\Configuration::get('DEF_SUPPLIER_PAYMENT_METHOD'))}});
                    }

                    $('#currency_id').val(response.currency_id);
                    $('#currency_conversion_rate').val(response.currency.conversion_rate);
                    $('#down_payment').val('0.0');

                    $('#invoicing_address_id').val(response.invoicing_address_id);
{{--
                    // https://www.youtube.com/watch?v=FHQh-IGT7KQ
                    $('#bank_id').empty();

    //                $('#bank_id').append('<option value="0" disable="true" selected="true">=== Select Address ===</option>');

                    $.each(response.addresses, function(index, element){
                      $('#bank_id').append('<option value="'+ element.id +'">'+ element.document_number +'</option>');
                    });

                    if ( response.bank_id > 0 ) {
                      $('#bank_id').val(response.bank_id);
                    } else {
                      $('#bank_id').val(response.invoicing_address_id);
                    }

                    if ( bank_id > 0 )
                      $("#bank_id").val( bank_id );

                    $('#warehouse_id').val({{ intval(\App\Configuration::get('DEF_WAREHOUSE'))}});

                    shipping_method_id = response.shipping_method_id;
                    if (shipping_method_id == null) {
                        shipping_method_id = "{{ intval(\App\Configuration::get('DEF_SHIPPING_METHOD'))}}";
                    }
                    $('#shipping_method_id').val( shipping_method_id );
--}}
                    $('#sales_rep_id').val(response.sales_rep_id);

                    console.log(response);
                }
            });
        }





    $( "#due_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    });

</script> 

@endsection
