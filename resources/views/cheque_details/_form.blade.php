
{{-- https://stackoverflow.com/questions/33413106/show-selected-option-in-bootstrap-dropdown-list-menu --}}

<!-- div class="row">

      <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('product_id') ? 'has-error' : '' }}">
        {!! Form::label('autoproduct_name', l('Product')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Reference.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>

        {!! Form::text('autoproduct_name', null, array('class' => 'form-control', 'id' => 'autoproduct_name')) !!}

        {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}
        {!! Form::hidden('measure_unit_id', old('measure_unit_id'), array('id' => 'measure_unit_id')) !!}

        {!! $errors->first('product_id', '<span class="help-block">:message</span>') !!}
      </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('quantity') ? 'has-error' : '' }}">
        {!! Form::label('quantity', l('Quantity')) !!}
        {!! Form::text('quantity', null, array('class' => 'form-control')) !!}
        {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
    </div>

</div -->

<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('line_sort_order') ? 'has-error' : '' }}">
        {!! Form::label('line_sort_order', l('Position')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use multiples of 10. Use other values to interpolate.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
        {!! Form::text('line_sort_order', null, array('class' => 'form-control')) !!}
        {!! $errors->first('line_sort_order', '<span class="help-block">:message</span>') !!}
    </div>

      <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', l('Name')) !!}
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
      </div>

</div>

<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('customer_invoice_id') ? 'has-error' : '' }}">
        {!! Form::label('auto_customer_invoice_reference', l('Customer Invoice')) !!}
        {!! Form::text('auto_customer_invoice_reference', old('auto_customer_invoice_reference', isset($chequedetail) ? $chequedetail->customer_invoice_reference : ''), array('class' => 'form-control')) !!}
        {!! $errors->first('customer_invoice_id', '<span class="help-block">:message</span>') !!}

        {{ Form::hidden('customer_invoice_id',        null, array('id' => 'customer_invoice_id'        )) }}
        {{ Form::hidden('customer_invoice_reference', null, array('id' => 'customer_invoice_reference' )) }}
    </div>    

    <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('amount') ? 'has-error' : '' }}">
        {!! Form::label('amount', l('Amount')) !!}
        <div class="input-group">
          {!! Form::text('amount', null, array('id' => 'amount', 'class' => 'form-control')) !!}
          <span class="input-group-addon">{{ $cheque->currency->sign }}</span>
        </div>
        {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
    </div>

</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('cheques.edit', l('Cancel', [], 'layouts'), array($cheque->id), array('class' => 'btn btn-warning')) !!}



@section('scripts')  @parent 


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">


    $(document).ready(function() {
{{--
      @if ( $chequedetail ?? null )
        $("#autoproduct_name").val('[{{ $chequedetail->product->reference }}] {{ $chequedetail->product->name }}');
        $("#product_id").val('{{ $chequedetail->product->id }}');
        $("#measure_unit_id").val('{{ $chequedetail->product->measure_unit_id }}');
      @else
        $("#autoproduct_name").val('');
        $("#product_id").val('');
        $("#measure_unit_id").val('');
      @endif
--}}

        // To get focus;
        $("#autoproduct_name").focus();

        $("#auto_customer_invoice_reference").autocomplete({
            source : "{{ route('chequedetail.searchinvoice', $cheque->id) }}?customer_id={{ $cheque->customer->id }}&currency_id={{ $cheque->currency_id }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                var str = '' + value.item.document_reference+' - ' + roundTo(value.item.total_tax_incl, 2);

                $("#auto_customer_invoice_reference").val(str);
                $('#customer_invoice_id').val(value.item.id);
                $('#customer_invoice_reference').val(value.item.document_reference);

                $('#amount').focus();

                // getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>' + item.document_reference+' - ' + roundTo(item.total_tax_incl, 2) + "</div>" )
                .appendTo( ul );
            };


        $( "form" ).submit(function( event ) {
              if ( $("#auto_customer_invoice_reference").val().trim() === "" ) {

                  $("#auto_customer_invoice_reference").val("");
                  $('#customer_invoice_id').val("");
                  $('#customer_invoice_reference').val("");
                  return;
              }
             
              // $( "span" ).text( "Not valid!" ).show().fadeOut( 1000 );
              // event.preventDefault();
        });

    });


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
                var str = '[' + response.identification+'] ' + response.name_fiscal;

                $("#autocustomer_name").val(str);
                $('#customer_id').val(response.id);


                $('#invoicing_address_id').val(response.invoicing_address_id);

                // https://www.youtube.com/watch?v=FHQh-IGT7KQ
                $('#address_id').empty();

//                $('#shipping_address_id').append('<option value="0" disable="true" selected="true">=== Select Address ===</option>');

                $.each(response.addresses, function(index, element){
                  $('#address_id').append('<option value="'+ element.id +'">['+ element.alias +'] '+element.name_commercial+'</option>');
                });

                if ( response.shipping_address_id > 0 ) {
                  $('#address_id').val(response.shipping_address_id);
                } else {
                  $('#address_id').val(response.invoicing_address_id);
                }

                console.log(response);
            }
        });
    }


    // Round numbers
    // https://stackoverflow.com/questions/15762768/javascript-math-round-to-two-decimal-places
    function roundTo(n, digits) {
        var negative = false;
        if (digits === undefined) {
            digits = 0;
        }
            if( n < 0) {
            negative = true;
          n = n * -1;
        }
        var multiplicator = Math.pow(10, digits);
        n = parseFloat((n * multiplicator).toFixed(11));
        n = (Math.round(n) / multiplicator).toFixed(2);
        if( negative ) {    
            n = (n * -1).toFixed(2);
        }
        return n;
    }



</script>

@endsection


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
