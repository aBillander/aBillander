
{!! Form::hidden('back_route', ($back_route ?? ''), array('id' => 'back_route')) !!}

        <div class="row">
            <div class="col-md-3">
                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                    {{ l('First Name', [], 'contacts') }}
                    {!! Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname')) !!}
                    {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                    {{ l('Last Name', [], 'contacts') }}
                    {!! Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname')) !!}
                    {!! $errors->first('lastname', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group {{ $errors->has('job_title') ? 'has-error' : '' }}">
                    {{ l('Job Title', [], 'contacts') }}
                    {!! Form::text('job_title', null, array('class' => 'form-control', 'id' => 'job_title')) !!}
                    {!! $errors->first('job_title', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('type') ? 'has-error' : '' }}">
                    {{ l('Type', [], 'contacts') }}
                    {!! Form::select('type', array('' => l('-- Please, select --', [], 'layouts')) + $contact_typeList, null, array('class' => 'form-control', 'id' => 'type')) !!}
                    {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    {{ l('Email', [], 'contacts') }}
                    {!! Form::text('email', null, array('class' => 'form-control', 'id' => 'email')) !!}
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-is_primary">
                     {!! Form::label('is_primary', l('Is primary?', 'contacts'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_primary', '1', false, ['id' => 'is_primary_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_primary', '0', true, ['id' => 'is_primary_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

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
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                    {{ l('Phone (regular)', [], 'contacts') }}
                    {!! Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone')) !!}
                    {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('phone_mobile') ? 'has-error' : '' }}">
                    {{ l('Phone (mobile)', [], 'contacts') }}
                    {!! Form::text('phone_mobile', null, array('class' => 'form-control', 'id' => 'phone_mobile')) !!}
                    {!! $errors->first('phone_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('address_id') ? 'has-error' : '' }}">
                    {{ l('Address', [], 'contacts') }}
                    {!! Form::select('address_id', array('' => l('-- Please, select --', [], 'layouts')) + $customer_addressList, null, array('class' => 'form-control', 'id' => 'address_id')) !!}
                    {!! $errors->first('address_id', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>



		{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
        {!! link_to( ($back_route != '' ? $back_route : 'contacts.index'), l('Cancel', [], 'layouts'), array('class' => 'btn btn-warning')) !!}


@section('scripts')  @parent 

    <script type="text/javascript">


    </script>

@endsection
