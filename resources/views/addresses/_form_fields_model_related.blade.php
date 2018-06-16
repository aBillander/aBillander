
	<div class="row">
	          <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('address.name_commercial') ? 'has-error' : '' }}">
	            {{ l('Name', [],'addresses') }}
	            {!! Form::text('address[name_commercial]', null, array('class' => 'form-control', 'id' => 'name_commercial')) !!}
	            {!! $errors->first('address.name_commercial', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div id="alias_field" name="alias_field" class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('address.alias') ? 'has-error' : '' }}">
	            {{ l('Alias', [],'addresses') }}
	            {!! Form::text('address[alias]', null, array('class' => 'form-control', 'id' => 'alias')) !!}
	            {!! $errors->first('address.alias', '<span class="help-block">:message</span>') !!}
	          </div>
	</div>

	<div class="row">
	    <div class="col-md-4">
	        <div class="form-group {{ $errors->has('address.firstname') ? 'has-error' : '' }}">
	            {{ l('Contact name', [],'addresses') }}
	            {!! Form::text('address[firstname]', null, array('class' => 'form-control', 'id' => 'firstname')) !!}
	            {!! $errors->first('address.firstname', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-4">
	        <div class="form-group {{ $errors->has('address.lastname') ? 'has-error' : '' }}">
	            {{ l('Contact surname', [],'addresses') }}
	            {!! Form::text('address[lastname]', null, array('class' => 'form-control', 'id' => 'lastname')) !!}
	            {!! $errors->first('address.lastname', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	</div>

	<div class="row">
	          <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('address.address1') ? 'has-error' : '' }}">
	            {{ l('Address (street, square, road...)', [],'addresses') }}
	            {!! Form::text('address[address1]', null, array('class' => 'form-control', 'id' => 'address1')) !!}
	            {!! $errors->first('address.address1', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('address.address2') ? 'has-error' : '' }}">
	            {{ l('Address 2 (quarter, building...)', [],'addresses') }}
	            {!! Form::text('address[address2]', null, array('class' => 'form-control', 'id' => 'address2')) !!}
	            {!! $errors->first('address.address2', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('address.postcode') ? 'has-error' : '' }}">
	            {{ l('Postal code', [],'addresses') }}
	            {!! Form::text('address[postcode]', null, array('class' => 'form-control', 'id' => 'postcode')) !!}
	            {!! $errors->first('address.postcode', '<span class="help-block">:message</span>') !!}
	          </div>
	</div>

	<div class="row">
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('address.city') ? 'has-error' : '' }}">
	            {{ l('City', [],'addresses') }}
	            {!! Form::text('address[city]', null, array('class' => 'form-control', 'id' => 'city')) !!}
	            {!! $errors->first('address.city', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('address.country_id') ? 'has-error' : '' }}">
	            {{ l('Country', [],'addresses') }}
	            {!! Form::select('address[country_id]', array('0' => l('-- Please, select --', [], 'layouts')) + $countryList, null, array('class' => 'form-control', 'id' => 'country_id')) !!}
	            {!! $errors->first('address.country_id', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('address.state_id') ? 'has-error' : '' }}">
	            {{ l('State', [],'addresses') }}
	            {!! Form::select('address[state_id]', array('0' => l('-- Please, select --', [], 'layouts')) + ( isset($stateList) ? $stateList : [] ), null, array('class' => 'form-control', 'id' => 'state_id')) !!}
	            {!! $errors->first('address.state_id', '<span class="help-block">:message</span>') !!}
	          </div>
	</div>

	<div class="row">
	    <div class="col-md-3">
	        <div class="form-group {{ $errors->has('address.phone') ? 'has-error' : '' }}">
	            {{ l('Phone (regular)', [],'addresses') }}
	            {!! Form::text('address[phone]', null, array('class' => 'form-control', 'id' => 'phone')) !!}
	            {!! $errors->first('address.phone', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-3">
	        <div class="form-group {{ $errors->has('address.phone_mobile') ? 'has-error' : '' }}">
	            {{ l('Phone (mobile)', [],'addresses', [],'addresses') }}
	            {!! Form::text('address[phone_mobile]', null, array('class' => 'form-control', 'id' => 'phone_mobile')) !!}
	            {!! $errors->first('address.phone_mobile', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-2">
	        <div class="form-group {{ $errors->has('address.fax') ? 'has-error' : '' }}">
	            {{ l('Fax', [],'addresses') }}
	            {!! Form::text('address[fax]', null, array('class' => 'form-control', 'id' => 'fax')) !!}
	            {!! $errors->first('address.fax', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-4">
	        <div class="form-group {{ $errors->has('address.email') ? 'has-error' : '' }}">
	            {{ l('Email', [],'addresses') }}
	            {!! Form::text('address[email]', null, array('class' => 'form-control', 'id' => 'email')) !!}
	            {!! $errors->first('address.email', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	</div>

	<div class="row">
	        <div id="notes_field" name="notes_field" class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('address.notes') ? 'has-error' : '' }}">
	          {{ l('Notes', [], 'layouts') }}
	          {!! Form::textarea('address[notes]', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
	          {!! $errors->first('address.notes', '<span class="help-block">:message</span>') !!}
	        </div>
	</div>


@section('scripts')  @parent 

    <script type="text/javascript">
        $('select[name="address[country_id]"]').change(function () {
            var countryID = $(this).val();
        	var stateID = {{ null !== old('address.state_id') ? old('address.state_id') : 
        				( isset($customer->address->state_id) ? $customer->address->state_id : 0 ) }};
            
            $.get('{{ url('/') }}/countries/' + countryID + '/getstates', function (states) {
                

                $('select[name="address[state_id]"]').empty();
                $('select[name="address[state_id]"]').append('<option value=0>{{ l('-- Please, select --', [], 'layouts') }}</option>');
                $.each(states, function (key, value) {
                    $('select[name="address[state_id]"]').append('<option value=' + value.id + '>' + value.name + '</option>');
                });
                
		        if ( stateID > 0 ) {
		        	$('select[name="address[state_id]"]').val(stateID);
		        }

            });
        });

        // Select default country
        if ( !($('select[name="address[country_id]"]').val() > 0) ) {
        	var def_countryID = {{ \App\Configuration::get('DEF_COUNTRY') }};

        	$('select[name="address[country_id]"]').val(def_countryID);
        }

        $('select[name="address[country_id]"]').change();

    </script>

@endsection
