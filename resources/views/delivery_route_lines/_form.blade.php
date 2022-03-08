
{{-- https://stackoverflow.com/questions/33413106/show-selected-option-in-bootstrap-dropdown-list-menu --}}

<div class="row">

      <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('customer_id') ? 'has-error' : '' }}">
        {!! Form::label('autocustomer_name', l('Customer')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>

        {!! Form::text('autocustomer_name', null, array('class' => 'form-control', 'id' => 'autocustomer_name')) !!}

        {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
        {!! Form::hidden('invoicing_address_id', old('invoicing_address_id'), array('id' => 'invoicing_address_id')) !!}

        {!! $errors->first('customer_id', '<span class="help-block">:message</span>') !!}
      </div>
      <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('address_id') ? 'has-error' : '' }}">
        {!! Form::label('address_id', l('Address')) !!}
        {!! Form::select('address_id', ['' => l('-- Please, select --', 'layouts')], null, array('class' => 'form-control', 'id' => 'address_id')) !!}
        {!! $errors->first('address_id', '<span class="help-block">:message</span>') !!}
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
	{!! link_to_route('deliveryroutes.deliveryroutelines.index', l('Cancel', [], 'layouts'), array($deliveryroute->id), array('class' => 'btn btn-warning')) !!}



@section('scripts')  @parent 


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">


    $(document).ready(function() {

        $("#autocustomer_name").val('');
        $("#customer_id").val('');
        $("#address_id").val('');

        // To get focus;
        $("#autocustomer_name").focus();

        $("#autocustomer_name").autocomplete({
            source : "{{ route('customerorders.ajax.customerLookup') }}",
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
