
<div class="row">

      <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('country_id') ? 'has-error' : '' }}">
        {!! Form::label('country_id', l('Country')) !!}
        {!! Form::select('country_id', array('' => l('-- All --', 'layouts')) + $countryList, null, array('class' => 'form-control', 'id' => 'country_id')) !!}
        {!! $errors->first('country_id', '<span class="help-block">:message</span>') !!}
      </div>
      <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('state_id') ? 'has-error' : '' }}">
        {!! Form::label('state_id', l('State')) !!}
        {!! Form::select('state_id', array('' => l('-- All --', 'layouts')) + ( isset($stateList) ? $stateList : [] ), null, array('class' => 'form-control', 'id' => 'state_id')) !!}
        {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}
      </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('postcode') ? 'has-error' : '' }}">
        {!! Form::label('postcode', l('Postal Code')) !!}
        {!! Form::text('postcode', null, array('class' => 'form-control')) !!}
        {!! $errors->first('postcode', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('from_amount') ? 'has-error' : '' }}">
        {!! Form::label('from_amount', l('From amount')) !!}
        <span class="label label-info">{{ $shippingmethod->billing_type_name }}</span>
        {!! Form::text('from_amount', old('from_amount', 0.0), array('class' => 'form-control')) !!}
        {!! $errors->first('from_amount', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('price') ? 'has-error' : '' }}">
        {!! Form::label('price', l('Price')) !!}
             <span class="badge" style="background-color: #3a87ad;" title="{{ AbiContext::getContext()->currency->name }}">{{ AbiContext::getContext()->currency->iso_code }}</span>
        {!! Form::text('price', old('price', 0.0), array('class' => 'form-control')) !!}
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
        $('select[name="country_id"]').change(function () {
            var countryID = $(this).val();
            
            $.get('{{ url('/') }}/countries/' + countryID + '/getstates', function (states) {
                

                $('select[name="state_id"]').empty();
                $('select[name="state_id"]').append('<option value="">{{ l('-- All --', 'layouts') }}</option>');
                $.each(states, function (key, value) {
                    $('select[name="state_id"]').append('<option value=' + value.id + '>' + value.name + '</option>');
                });
            });
        });

        // Select default country
        if ( !($('select[name="country_id"]').val().length > 0) ) {
            var def_countryID = {{ AbiConfiguration::get('DEF_COUNTRY') }};

            $('select[name="country_id"]').val(def_countryID).change();
        }

    </script>

@endsection
