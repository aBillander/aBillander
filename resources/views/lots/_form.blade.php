
{{-- https://stackoverflow.com/questions/33413106/show-selected-option-in-bootstrap-dropdown-list-menu --}}

<div class="row">

          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('reference') ? 'has-error' : '' }}" id="div-reference">
             {{ l('Lot Number') }}
             {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
             {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
          </div>


          <div class="form-group col-lg-4 col-md-4 col-sm-4">
             {{-- Poor man offset --}}
          </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4  hide " id="div-use_current_stock">
           {!! Form::label('use_current_stock', l('Use Stock?'), ['class' => 'control-label']) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use Stock not allocated to Lots, if there is enough quantity. If you choose "No", Lot quantity will appear as a Stock Adjustment.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
           <div>
             <div class="radio-inline">
               <label>
                 {!! Form::radio('use_current_stock', '1', false, ['id' => 'use_current_stock_on']) !!}
                 {!! l('Yes', [], 'layouts') !!}
               </label>
             </div>
             <div class="radio-inline">
               <label>
                 {!! Form::radio('use_current_stock', '0', true, ['id' => 'use_current_stock_off']) !!}
                 {!! l('No', [], 'layouts') !!}
               </label>
             </div>
           </div>
         </div>

</div>

<div class="row">

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

</div>

<div class="row">

                 <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('manufactured_at_form') ? 'has-error' : '' }}">
                     {{ l('Manufacture Date') }}
                     {!! Form::text('manufactured_at_form', null, array('class' => 'form-control', 'id' => 'manufactured_at_form')) !!}
                     {!! $errors->first('manufactured_at_form', '<span class="help-block">:message</span>') !!}
                  </div>


                 <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('expiry_at_form') ? 'has-error' : '' }}">
                     {{ l('Expiry Date') }}
                     {!! Form::text('expiry_at_form', null, array('class' => 'form-control', 'id' => 'expiry_at_form')) !!}
                     {!! $errors->first('expiry_at_form', '<span class="help-block">:message</span>') !!}
                  </div>

{{--
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('expiry_time') ? 'has-error' : '' }}" id="div-expiry_time">
                     {{ l('Expiry Time') }}
                             <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                                                data-content="{{ l('Number of Days before expiry.') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                             </a>
                     {!! Form::text('expiry_time', null, array('class' => 'form-control', 'id' => 'expiry_time')) !!}
                     {!! $errors->first('expiry_time', '<span class="help-block">:message</span>') !!}
                  </div>
--}}
         
         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            {{ l('Warehouse') }}
            {!! Form::select('warehouse_id', \App\Models\Warehouse::selectorList(), null, array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
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
	{!! link_to_route('lots.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}



@section('scripts')  @parent 


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">


    $(document).ready(function() {
/*
      @ if ( $customerordertemplateline ?? null )
        $("#autoproduct_name").val('[{ { $customerordertemplateline->product->reference }}] { { $customerordertemplateline->product->name }}');
        $("#product_id").val('{ { $customerordertemplateline->product->id }}');
        $("#measure_unit_id").val('{ { $customerordertemplateline->product->measure_unit_id }}');
      @ else
        $("#autoproduct_name").val('');
        $("#product_id").val('');
        $("#measure_unit_id").val('');
      @ endif
*/

        // To get focus;
        $("#autoproduct_name").focus();

        $("#autoproduct_name").autocomplete({
            source : "{{ route('home.searchproduct') }}",
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




</script>


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>


  $(function() {
    $( "#manufactured_at_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });


  $(function() {
    $( "#expiry_at_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

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

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>


@endsection
