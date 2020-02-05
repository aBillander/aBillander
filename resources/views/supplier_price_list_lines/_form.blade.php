
<div class="row">

         {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {{ l('From Quantity') }}
        {!! Form::text('from_quantity', null, array('class' => 'form-control', 'id' => 'from_quantity', 'onclick' => 'this.select()')) !!}
    </div>

      <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price') ? 'has-error' : '' }}">
         {{ l('Price') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Price is WITHOUT Taxes.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
         {!! Form::text('price', null, array('class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off', 
                          'onclick' => 'this.select()')) !!}
         {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
      </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {{ l('Currency') }}
        {!! Form::select('currency_id', $currencyList, null, array('class' => 'form-control', 'id' => 'currency_id')) !!}
        {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
    </div>

</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('suppliers.supplierpricelistlines.index', l('Cancel', [], 'layouts'), array($supplier->id), array('class' => 'btn btn-warning')) !!}


{{--
@section('scripts')  @parent 

    <script type="text/javascript">


    </script>

@endsection
--}}