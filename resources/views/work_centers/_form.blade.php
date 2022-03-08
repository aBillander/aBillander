
<div class="panel-body">
  <div class="row">
            <div id="alias_field" name="alias_field" class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('alias') ? 'has-error' : '' }}">
              {{ l('Alias') }}
              {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
              {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
              {{ l('Name') }}
              {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
              {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>

           <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-active">
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

</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">
  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button>
</div>
