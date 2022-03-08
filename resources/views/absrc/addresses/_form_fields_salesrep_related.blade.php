
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
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('address.state') ? 'has-error' : '' }}">
	            {{ l('State', [],'addresses') }}
	            {!! Form::text('address[state]', null, array('class' => 'form-control', 'id' => 'state')) !!}
	            {!! $errors->first('address.state', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('address.country') ? 'has-error' : '' }}">
	            {{ l('Country', [],'addresses') }}
	            {!! Form::text('address[country]', null, array('class' => 'form-control', 'id' => 'country')) !!}
	            {!! $errors->first('address.country', '<span class="help-block">:message</span>') !!}
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
