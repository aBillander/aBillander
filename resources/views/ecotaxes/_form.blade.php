
<div class="row">
    <div class="form-group col-lg-8 col-md-8 col-sm-8">
        {!! Form::label('name', l('Ecotax name')) !!}
        {!! Form::text('name', null, array('class' => 'form-control')) !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('percent') ? 'has-error' : '' }}">
        {!! Form::label('percent', l('Ecotax Percent')) !!}
        {!! Form::text('percent', null, array('class' => 'form-control')) !!}
        {!! $errors->first('percent', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('amount') ? 'has-error' : '' }}">
        {!! Form::label('amount', l('Ecotax Amount')) !!}
             <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use this field when tax is a fixed amount per item.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
        {!! Form::text('amount', null, array('class' => 'form-control')) !!}
        {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
    </div>
</div>
{{--
<div class="row">
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
</div>
--}}
	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('ecotaxes.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
