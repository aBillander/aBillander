
<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('alias') ? 'has-error' : '' }}">
        {!! Form::label('alias', l('Alias')) !!}
        {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
        {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', l('Customer Order Template name')) !!}
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('customer_id') ? 'has-error' : '' }}">
       {!! Form::label('autocustomer_name', l('Customer')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).', 'customerorders') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('autocustomer_name', null, array('class' => 'form-control', 'id' => 'autocustomer_name')) !!}
        {!! $errors->first('customer_id', '<span class="help-block">:message</span>') !!}

        {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
    </div>

</div>

<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-active">
     {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('active', '1', true, ['id' => 'active_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('active', '0', false, ['id' => 'active_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
     </div>
    </div>

    <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('notes') ? 'has-error' : '' }}">
       {!! Form::label('notes', l('Notes', [], 'layouts')) !!}
       {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
       {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
    </div>
</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('customerordertemplates.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}




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

@endsection


@section('scripts')    @parent


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

    $(document).ready(function() {

      @if ( $customerordertemplate ?? null )
        $("#autocustomer_name").val('{{ $customerordertemplate->customer->name_regular }}');
        $("#customer_id").val('{{ $customerordertemplate->customer->id }}');
      @else
        $("#autocustomer_name").val('');
        $("#customer_id").val('');
      @endif

        // To get focus;
        // $("#autocustomer_name").focus();

        $("#autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route('customerorders.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {
                // var str = '[' + value.item.identification+'] ' + value.item.name_regular + ' [' + value.item.reference_external +']';
                var str = value.item.name_regular + ' [' + value.item.reference_external +']';

                // getCustomerData( value.item.id );

                $("#autocustomer_name").val(str);
                $('#customer_id').val(value.item.id );

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
                url: "{{ route('customerorders.ajax.customerLookup') }}",
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

@endsection
