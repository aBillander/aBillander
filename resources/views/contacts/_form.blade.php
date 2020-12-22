
<div class="row">
            <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('firstname') ? 'has-error' : '' !!}">
              {{ l('First Name') }}
              {!! Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname')) !!}
              {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('lastname') ? 'has-error' : '' }}">
              {{ l('Last Name') }}
              {!! Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname')) !!}
              {!! $errors->first('lastname', '<span class="help-block">:message</span>') !!}
            </div>

         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('party_id') ? 'has-error' : '' }}">

{{--
@if ($party ?? null)
            {{ Form::hidden('party_id', $party->id, array('id' => 'party_id')) }}
@else
            {{ l('Party') }}
            {!! Form::select('party_id', ['' => l('-- Please, select --', 'layouts')] + $partyList, null, array('class' => 'form-control', 'id' => 'party_id')) !!}
            {!! $errors->first('party_id', '<span class="help-block">:message</span>') !!}
@endif
--}}
            {{ l('Party') }}
            {!! Form::select('party_id', (count($partyList) >1 ? ['' => l('-- Please, select --', 'layouts')] : []) + $partyList, null, array('class' => 'form-control', 'id' => 'party_id')) !!}
            {!! $errors->first('party_id', '<span class="help-block">:message</span>') !!}

         </div>
</div>

<div class="row">

            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('job_title') ? 'has-error' : '' !!}">
              {{ l('Job Title') }}
              {!! Form::text('job_title', null, array('class' => 'form-control', 'id' => 'job_title')) !!}
              {!! $errors->first('job_title', '<span class="help-block">:message</span>') !!}
            </div>

      <div class="col-md-4">
          <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
              {{ l('Email') }}
              {!! Form::text('email', null, array('class' => 'form-control', 'id' => 'email')) !!}
              {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
          </div>
      </div>
      <div class="col-md-3">
          <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
              {{ l('Phone (regular)') }}
              {!! Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone')) !!}
              {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
          </div>
      </div>
      <div class="col-md-3">
          <div class="form-group {{ $errors->has('phone_mobile') ? 'has-error' : '' }}">
              {{ l('Phone (mobile)') }}
              {!! Form::text('phone_mobile', null, array('class' => 'form-control', 'id' => 'phone_mobile')) !!}
              {!! $errors->first('phone_mobile', '<span class="help-block">:message</span>') !!}
          </div>
      </div>

</div>

<div class="row">

        <div class="form-group col-lg-6 col-md-6 col-sm-6 {!! $errors->has('address') ? 'has-error' : '' !!}">
          {{ l('Address') }}
          {!! Form::text('address', null, array('class' => 'form-control', 'id' => 'address')) !!}
          {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
        </div>

        <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('website') ? 'has-error' : '' !!}">
          {{ l('Website') }}
          {!! Form::text('website', null, array('class' => 'form-control', 'id' => 'website')) !!}
          {!! $errors->first('website', '<span class="help-block">:message</span>') !!}
        </div>
</div>

<div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-blocked">
                     {!! Form::label('blocked', l('Blocked?', [], 'layouts'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('blocked', '1', false, ['id' => 'blocked_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('blocked', '0', true, ['id' => 'blocked_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
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
          <div id="notes_field" name="notes_field" class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
            {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
          </div>
  </div>

  {{  Form::hidden('caller_url', URL::previous())  }}

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to(url()->previous(), l('Cancel', [], 'layouts'), array('class' => 'btn btn-warning')) !!}

