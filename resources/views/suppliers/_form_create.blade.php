
         <div class="panel-body">

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


                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-approved">
                     {!! Form::label('approved', l('Approved?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('approved', '1', true, ['id' => 'approved_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('approved', '0', false, ['id' => 'approved_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>


            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('approval_number') ? 'has-error' : '' !!}">
              {{ l('Approval Number') }}
              {!! Form::text('approval_number', null, array('class' => 'form-control', 'id' => 'approval_number')) !!}
              {!! $errors->first('approval_number', '<span class="help-block">:message</span>') !!}
            </div>
  </div>

        <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
              {{ l('Notes', [], 'layouts') }}
              {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
              {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

@include('addresses._form_fields_model_supplier')

         </div><!-- div class="panel-body" -->

         <div class="panel-footer text-right">
            <a class="btn btn-link" data-dismiss="modal" href="{!! URL::to('suppliers') !!}">{{l('Cancel', [], 'layouts')}}</a>
            <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
               <i class="fa fa-floppy-o"></i>
               &nbsp; {{l('Save', [], 'layouts')}}
            </button>
            <input type="hidden" id="nextAction" name="nextAction" value="" />
            <button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#nextAction').val('completeSupplierData');this.form.submit();">
               <i class="fa fa-hdd-o"></i>
               &nbsp; {{l('Save & Complete', [], 'layouts')}}
            </button>
         </div>


@section('scripts')  @parent 

    <script type="text/javascript">

        // Hide Alias field
        $('#alias_field').hide();

        // Hide Notes field
        $('#notes_field').hide();

    </script>

@endsection
