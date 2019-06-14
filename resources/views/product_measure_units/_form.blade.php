
<div class="row">

     <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
        {{ l('Measure Unit') }}
        {!! Form::select('measure_unit_id', array('0' => l('-- Please, select --', [], 'layouts')) + $measure_unitList, null, array('class' => 'form-control')) !!}
        {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
     </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('conversion_rate') ? 'has-error' : '' }}">
        {!! Form::label('conversion_rate', l('Conversion rate')) !!}
             <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ l('Conversion rates are calculated from one unit of your main Measura Unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, the Conversion rate will be "6" (since a pack of six bottles will contain six bottles).') }}">
                    <i class="fa fa-question-circle abi-help"></i>
             </a>
        {!! Form::text('conversion_rate', null, array('class' => 'form-control')) !!}
        {!! $errors->first('conversion_rate', '<span class="help-block">:message</span>') !!}
    </div>
</div>

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

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('products.measureunits.index', l('Cancel', [], 'layouts'), array($product->id), array('class' => 'btn btn-warning')) !!}

