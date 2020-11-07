
            <div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>
               <div class="panel-body">

<!-- Datos generales -->

        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 {!! $errors->has('name_fiscal') ? 'has-error' : '' !!}">
              {{ l('Fiscal Name') }}
              {!! Form::text('name_fiscal', null, array('class' => 'form-control', 'id' => 'name_fiscal')) !!}
              {!! $errors->first('name_fiscal', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('identification') ? 'has-error' : '' !!}">
              {{ l('Identification') }}
              {!! Form::text('identification', null, array('class' => 'form-control', 'id' => 'identification')) !!}
              {!! $errors->first('identification', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('approval_number') ? 'has-error' : '' !!}">
              {{ l('Approval Number') }}
              {!! Form::text('approval_number', null, array('class' => 'form-control', 'id' => 'approval_number')) !!}
              {!! $errors->first('approval_number', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('reference_external') ? 'has-error' : '' !!}">
              {{ l('External Reference') }}
              {!! Form::text('reference_external', null, array('class' => 'form-control', 'id' => 'reference_external')) !!}
              {!! $errors->first('reference_external', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

@include('addresses._form_fields_model_supplier')

        <div class="row">
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
            <div class="well well-sm" style="background-color: #d9edf7; border-color: #bce8f1; color: #3a87ad;">
               <b>{{ l('Misc') }}</b>
            </div>
            </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('website') ? 'has-error' : '' !!}">
                    {{ l('Website') }}
                    {!! Form::text('website', null, array('class' => 'form-control', 'id' => 'website')) !!}
                    {!! $errors->first('website', '<span class="help-block">:message</span>') !!}
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


                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-creditor">
                     {!! Form::label('creditor', l('Is Creditor?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('creditor', '1', true, ['id' => 'creditor_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('creditor', '0', false, ['id' => 'creditor_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

        </div>

        <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
              {{ l('Notes', [], 'layouts') }}
              {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
              {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

<!-- Datos generales ENDS -->

               </div>
               <div class="panel-footer text-right">
                  <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}
                  </button>
               </div>
            </div>


@section('scripts')  @parent 

    <script type="text/javascript">

        // Hide Alias field
        $('#alias_field').hide();

        // Hide Notes field
        $('#notes_field').hide();

        // Disable Main Address edition
        $("#address1").attr( {"disabled" : "disabled"} );
        $("#address2").attr( {"disabled" : "disabled"} );
        $("#postcode").attr( {"disabled" : "disabled"} );

        $("#city").attr( {"disabled" : "disabled"} );
        $("#country_id").attr( {"disabled" : "disabled"} );
        $("#state_selector").attr( {"disabled" : "disabled"} );

    </script>

@endsection
