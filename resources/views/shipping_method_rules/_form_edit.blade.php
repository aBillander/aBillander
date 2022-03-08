
<div class="row">

      <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('country_id', l('Country')) !!}
        <div class="form-control">{{ optional($rule->country)->name ?: l('-- All --', 'layouts') }}</div>
      </div>
      <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('state_id', l('State')) !!}
        <div class="form-control">{{ optional($rule->state)->name ?: l('-- All --', 'layouts') }}</div>
      </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('postcode') ? 'has-error' : '' }}">
        {!! Form::label('postcode', l('Postal Code')) !!}
        <div class="form-control">{{ $rule->postcode ?: l('-- All --', 'layouts') }}</div>
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('from_amount') ? 'has-error' : '' }}">
        {!! Form::label('from_amount', l('From amount')) !!}
        <span class="label label-info">{{ $shippingmethod->billing_type_name }}</span>
        {!! Form::text('from_amount', null, array('class' => 'form-control')) !!}
        {!! $errors->first('from_amount', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('price') ? 'has-error' : '' }}">
        {!! Form::label('price', l('Price')) !!}
             <span class="badge" style="background-color: #3a87ad;" title="{{ AbiContext::getContext()->currency->name }}">{{ AbiContext::getContext()->currency->iso_code }}</span>
        {!! Form::text('price', null, array('class' => 'form-control')) !!}
        {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
    </div>
</div>
{{--
<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('position') ? 'has-error' : '' }}">
        {!! Form::label('position', l('Position')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use multiples of 10. Use other values to interpolate.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
        {!! Form::text('position', null, array('class' => 'form-control')) !!}
        {!! $errors->first('position', '<span class="help-block">:message</span>') !!}
    </div>
</div>
--}}
	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('shippingmethods.shippingmethodrules.index', l('Cancel', [], 'layouts'), array($shippingmethod->id), array('class' => 'btn btn-warning')) !!}



@section('scripts')  @parent 

    <script type="text/javascript">

        // Select input field
        $("#from_amount").focus();

    </script>

@endsection
