
<div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('alias') ? 'has-error' : '' }}">
                      {!! Form::label('alias', l('Alias','layouts')) !!}
                      {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
                      {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
                  </div>

      <div class="form-group col-lg-9 col-md-9 col-sm-9">
          {!! Form::label('name', l('Shipping Method name')) !!}
          {!! Form::text('name', null, array('class' => 'form-control')) !!}
      </div>

</div>
<div class="row">

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('carrier_id') ? 'has-error' : '' }}">
                     {!! Form::label('carrier_id', l('Carrier')) !!}
                     {!! Form::select('carrier_id', array('' => l('-- Please, select --', [], 'layouts')) + $carrierList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('carrier_id', '<span class="help-block">:message</span>') !!}
                  </div>

      <div class="form-group col-lg-8 col-md-8 col-sm-8">
          {!! Form::label('class_name', l('Class name')) !!}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Class fully qualified name') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
          {!! Form::text('class_name', null, array('class' => 'form-control')) !!}
      </div>

</div>

<div class="row">

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

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('shippingmethods.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
