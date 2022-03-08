
<div class="form-group">
		{!! Form::label('name', l('Price List Name')) !!}
		{!! Form::text('name', null, array('placeholder' => '', 'class' => 'form-control')) !!}
</div>

<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
    		{!! Form::label('type', l('Price List Type')) !!}
    		{!! Form::select('type', $price_list_typeList, null, array('class' => 'form-control', 'id' => 'type')) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6" id="div-amount">
    		{!! Form::label('amount', l('Amount')) !!}
    		{!! Form::text('amount', null, array('placeholder' => '0.0', 'class' => 'form-control', 'id' => 'amount')) !!}
    </div>
</div>

<div class="row">
   <div class="form-group col-lg-6 col-md-6 col-sm-6" id="div-price_is_tax_inc">
     {!! Form::label('price_is_tax_inc', l('Price is Tax Included?'), ['class' => 'control-label']) !!}
     <div class="">
       <div class="radio-inline">
         <label>
           {!! Form::radio('price_is_tax_inc', '1', false, ['id' => 'price_is_tax_inc_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('price_is_tax_inc', '0', true, ['id' => 'price_is_tax_inc_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
     </div>
   </div>

   <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
      {!! Form::label('currency_id', l('Currency')) !!}
      {!! Form::select('currency_id', array('0' => l('-- Please, select --', [], 'layouts')) + $currencyList, null, array('class' => 'form-control')) !!}
      {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
   </div>

</div>

<input type="hidden" id="addAction" name="addAction" value="" />

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
<!-- 
{!! Form::submit(l('Save & Complete', [], 'layouts'), array('class' => 'btn btn-info', 'onclick' => "this.disabled=true;$('#addAction').val('updateProducts');this.form.submit();")) !!}
-->
{!! link_to_route('pricelists.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}


@section('scripts')    @parent

    <script type="text/javascript">
        // Set default
        if ( !$('input[name="amount"]').val() ) {
          $('input[name="amount"]').val('0.0');
        }

        // Select default currency
        if ( !($('select[name="currency_id"]').val() > 0) ) {
          var def_currencyID = {{ \App\Configuration::get('DEF_CURRENCY') }};

          $('select[name="currency_id"]').val(def_currencyID);
        }

    </script>


@endsection




@section('scripts')  @parent 

<script type="text/javascript">

$(function() {

    if ($('#type').val() == 'price') {
 //           $('#div-price_is_tax_inc').hide();
 //           $('#div-amount').show();
 //       }
 //       else {
            $('#div-amount').hide();
            $('#amount').val( 0.0 );
    };

    $('#type').change(function () {
        if ($(this).val() != 'price') {
//            $('#div-price_is_tax_inc').hide();
            $('#div-amount').show();
        }
        else {
            $('#div-amount').hide();
            $('#amount').val( 0.0 );
//            $('#div-price_is_tax_inc').show();
        }
    });

/*
    $('input[name=recurring]:radio').change(function () {
        if ($(this).val() == 1) {
            $('#div-recurring-options').show();
        }
        else {
            $('#div-recurring-options').hide();
        }
    });
*/

});

</script>

@endsection
