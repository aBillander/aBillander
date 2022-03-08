
<div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('alias') ? 'has-error' : '' }}">
                      {!! Form::label('alias', l('Alias','layouts')) !!}
                      {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
                      {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
                  </div>

<div class="form-group col-lg-7 col-md-7 col-sm-7">
    {!! Form::label('name', l('Payment Type Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
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

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('accounting_code', l('Accounting Code')) !!}
        {!! Form::text('accounting_code', null, array('class' => 'form-control')) !!}
    </div>

</div>


{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('paymenttypes.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
