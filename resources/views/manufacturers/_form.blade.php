
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('name', l('Manufacturer name')) !!}
        {!! Form::text('name', null, array('class' => 'form-control')) !!}
    </div>
</div>

<!-- div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-active">
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
</div -->

  {!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
  {!! link_to_route('manufacturers.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
