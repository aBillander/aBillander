
<div class="row">
            <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('name_fiscal') ? 'has-error' : '' !!}">
              {{ l('Fiscal Name') }}
              {!! Form::text('name_fiscal', null, array('class' => 'form-control', 'id' => 'name_fiscal')) !!}
              {!! $errors->first('name_fiscal', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('name_commercial') ? 'has-error' : '' }}">
              {{ l('Commercial Name') }}
              {!! Form::text('name_commercial', null, array('class' => 'form-control', 'id' => 'name_commercial')) !!}
              {!! $errors->first('name_commercial', '<span class="help-block">:message</span>') !!}
            </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('type') ? 'has-error' : '' }}">
            {{ l('Type') }}
            {!! Form::select('type', $party_typeList, null, array('class' => 'form-control', 'id' => 'type')) !!}
            {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
         </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('identification') ? 'has-error' : '' !!}">
              {{ l('Identification') }}
              {!! Form::text('identification', null, array('class' => 'form-control', 'id' => 'identification')) !!}
              {!! $errors->first('identification', '<span class="help-block">:message</span>') !!}
            </div>
</div>

<div class="row">
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


         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('user_assigned_to_id') ? 'has-error' : '' }}">
            {{ l('Assigned to') }}
            {!! Form::select('user_assigned_to_id', $userList, null, array('class' => 'form-control', 'id' => 'user_assigned_to_id')) !!}
            {!! $errors->first('user_assigned_to_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>

  <div class="row">
          <div id="notes_field" name="notes_field" class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
            {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
          </div>
  </div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('parties.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
