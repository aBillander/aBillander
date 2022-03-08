
<div class="row">

      <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('country_id') ? 'has-error' : '' }}">
        {!! Form::label('country_id', l('Country')) !!}
        {!! Form::select('country_id', array('0' => l('-- All --')) + $countryList, null, array('class' => 'form-control', 'id' => 'country_id')) !!}
        {!! $errors->first('country_id', '<span class="help-block">:message</span>') !!}
      </div>
      <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('state_id') ? 'has-error' : '' }}">
        {!! Form::label('state_id', l('State')) !!}
        {!! Form::select('state_id', array('0' => l('-- All --')) + ( isset($stateList) ? $stateList : [] ), null, array('class' => 'form-control', 'id' => 'state_id')) !!}
        {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}
      </div>

</div>

<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', l('Ecotax Rule Name')) !!}
        {!! Form::text('name', null, array('class' => 'form-control')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('rule_type') ? 'has-error' : '' }}">
        {!! Form::label('rule_type', l('Ecotax Rule Type')) !!}
        {!! Form::select('rule_type', $ecotax_rule_typeList, null, array('class' => 'form-control', 'id' => 'rule_type')) !!}
        {!! $errors->first('rule_type', '<span class="help-block">:message</span>') !!}
      </div>
</div>

<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('percent') ? 'has-error' : '' }}">
        {!! Form::label('percent', l('Ecotax Rule Percent')) !!}
        {!! Form::text('percent', null, array('class' => 'form-control')) !!}
        {!! $errors->first('percent', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('amount') ? 'has-error' : '' }}">
        {!! Form::label('amount', l('Ecotax Rule Amount')) !!}
             <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use this field when tax is a fixed amount per item.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
        {!! Form::text('amount', null, array('class' => 'form-control')) !!}
        {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
    </div>
</div>

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

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('ecotaxes.ecotaxrules.index', l('Cancel', [], 'layouts'), array($ecotax->id), array('class' => 'btn btn-warning')) !!}



@section('scripts')  @parent 

    <script type="text/javascript">
        $('select[name="country_id"]').change(function () {
            var countryID = $(this).val();
            
            $.get('{{ url('/') }}/countries/' + countryID + '/getstates', function (states) {
                

                $('select[name="state_id"]').empty();
                $('select[name="state_id"]').append('<option value=0>{{ l('-- All --') }}</option>');
                $.each(states, function (key, value) {
                    $('select[name="state_id"]').append('<option value=' + value.id + '>' + value.name + '</option>');
                });
            });
        });

        // Select default country
        if ( !($('input[name="name"]').val().length > 0) ) {
            var def_countryID = {{ AbiConfiguration::get('DEF_COUNTRY') }};

            $('select[name="country_id"]').val(def_countryID).change();
        }

    </script>

@endsection
