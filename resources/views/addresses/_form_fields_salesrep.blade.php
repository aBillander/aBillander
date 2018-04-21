
	<div class="row">
	    <div class="col-md-4">
	        <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
	            {{ l('Contact name', [],'addresses') }}
	            {!! Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname')) !!}
	            {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-4">
	        <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
	            {{ l('Contact surname', [],'addresses') }}
	            {!! Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname')) !!}
	            {!! $errors->first('lastname', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	</div>

	<div class="row">
	          <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('address1') ? 'has-error' : '' }}">
	            {{ l('Address (street, square, road...)', [],'addresses') }}
	            {!! Form::text('address1', null, array('class' => 'form-control', 'id' => 'address1')) !!}
	            {!! $errors->first('address1', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('address2') ? 'has-error' : '' }}">
	            {{ l('Address 2 (quarter, building...)', [],'addresses') }}
	            {!! Form::text('address2', null, array('class' => 'form-control', 'id' => 'address2')) !!}
	            {!! $errors->first('address2', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('postcode') ? 'has-error' : '' }}">
	            {{ l('Postal code', [],'addresses') }}
	            {!! Form::text('postcode', null, array('class' => 'form-control', 'id' => 'postcode')) !!}
	            {!! $errors->first('postcode', '<span class="help-block">:message</span>') !!}
	          </div>
	</div>

	<div class="row">
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('city') ? 'has-error' : '' }}">
	            {{ l('City', [],'addresses') }}
	            {!! Form::text('city', null, array('class' => 'form-control', 'id' => 'city')) !!}
	            {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('state') ? 'has-error' : '' }}">
	            {{ l('State', [],'addresses') }}
	            {!! Form::text('state', null, array('class' => 'form-control', 'id' => 'state')) !!}
	            {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('country') ? 'has-error' : '' }}">
	            {{ l('Country', [],'addresses') }}
	            {!! Form::text('country', null, array('class' => 'form-control', 'id' => 'country')) !!}
	            {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
	          </div>
	</div>

	<div class="row">
	    <div class="col-md-3">
	        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
	            {{ l('Phone (regular)', [],'addresses') }}
	            {!! Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone')) !!}
	            {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-3">
	        <div class="form-group {{ $errors->has('phone_mobile') ? 'has-error' : '' }}">
	            {{ l('Phone (mobile)', [],'addresses') }}
	            {!! Form::text('phone_mobile', null, array('class' => 'form-control', 'id' => 'phone_mobile')) !!}
	            {!! $errors->first('phone_mobile', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-2">
	        <div class="form-group {{ $errors->has('fax') ? 'has-error' : '' }}">
	            {{ l('Fax', [],'addresses') }}
	            {!! Form::text('fax', null, array('class' => 'form-control', 'id' => 'fax')) !!}
	            {!! $errors->first('fax', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	    <div class="col-md-4">
	        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
	            {{ l('Email', [],'addresses') }}
	            {!! Form::text('email', null, array('class' => 'form-control', 'id' => 'email')) !!}
	            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
	        </div>
	    </div>
	</div>
