
{{-- https://stackoverflow.com/questions/33413106/show-selected-option-in-bootstrap-dropdown-list-menu --}}

<div class="row">

      <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('product_id') ? 'has-error' : '' }}">
        {!! Form::label('autoproduct_name', l('Product')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Reference.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>

        {!! Form::text('autocustomer_name', null, array('class' => 'form-control', 'id' => 'autoproduct_name')) !!}

        {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}
        {!! Form::hidden('measure_unit_id', old('measure_unit_id'), array('id' => 'measure_unit_id')) !!}

        {!! $errors->first('product_id', '<span class="help-block">:message</span>') !!}
      </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('quantity') ? 'has-error' : '' }}">
        {!! Form::label('quantity', l('Quantity')) !!}
        {!! Form::text('quantity', null, array('class' => 'form-control')) !!}
        {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
    </div>

</div>

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

</div>

<div class="row">
    <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('notes') ? 'has-error' : '' }}">
       {!! Form::label('notes', l('Notes', [], 'layouts')) !!}
       {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
       {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
    </div>
</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('customerordertemplates.customerordertemplatelines.index', l('Cancel', [], 'layouts'), array($customerordertemplate->id), array('class' => 'btn btn-warning')) !!}



@section('scripts')  @parent 


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">


    $(document).ready(function() {

      @if ( $customerordertemplateline ?? null )
        $("#autoproduct_name").val('[{{ $customerordertemplateline->product->reference }}] {{ $customerordertemplateline->product->name }}');
        $("#product_id").val('{{ $customerordertemplateline->product->id }}');
        $("#measure_unit_id").val('{{ $customerordertemplateline->product->measure_unit_id }}');
      @else
        $("#autoproduct_name").val('');
        $("#product_id").val('');
        $("#measure_unit_id").val('');
      @endif


        // To get focus;
        $("#autoproduct_name").focus();

        $("#autoproduct_name").autocomplete({
            source : "{{ route('customerorders.searchproduct') }}?customer_id={{ $customerordertemplate->customer->id }}&currency_id={{ $customerordertemplate->customer->currency_id }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                var str = '[' + value.item.reference+'] ' + value.item.name;

                $("#autoproduct_name").val(str);
                $('#product_id').val(value.item.id);
                $('#measure_unit_id').val(value.item.measure_unit_id);

                $('#quantity').focus();

                // getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };

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
