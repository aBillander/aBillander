
<div class="panel-body">
  <div class="row">

            <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
               {{ l('Reference') }}
               {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
               {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
            </div>

            <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('name') ? 'has-error' : '' }}">
              {{ l('Name') }}
              {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
              {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>
            
            <div id="tool_type_field" name="tool_type_field" class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('tool_type') ? 'has-error' : '' }}">
              {{ l('Tool type') }}
              {!! Form::text('tool_type', null, array('class' => 'form-control', 'id' => 'tool_type')) !!}
              {!! $errors->first('tool_type', '<span class="help-block">:message</span>') !!}
            </div>

           <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('location') ? 'has-error' : '' }}">
                     {{ l('Location') }}
                     {!! Form::text('location', null, array('class' => 'form-control', 'id' => 'location')) !!}
                     {!! $errors->first('location', '<span class="help-block">:message</span>') !!}
           </div>
  </div>

  <div class="row">
          <div id="notes_field" name="notes_field" class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('description') ? 'has-error' : '' }}">
                     {{ l('Description') }}
                     {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'rows' => '3')) !!}
                     {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
          </div>
  </div>

</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">
  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('tools') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button>
</div>
