

<div class="row">
    <div class=" hide form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('from_quantity_pack', l('From Quantity')) !!}
        {!! Form::text('from_quantity_pack', old('from_quantity_pack', 1), array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
        {!! Form::label('measure_unit_id', l('Package')) !!}
        {!! Form::select('measure_unit_id', ['0' => l('-- Please, select --', [], 'layouts')], null, array('class' => 'form-control', 'id' => 'measure_unit_id')) !!}
        {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('price_pack', l('Price')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" 
                                    data-content="{{ l('Price is WITHOUT Taxes.') }}
@if( \App\Configuration::isTrue('ENABLE_ECOTAXES') )
    <br />
    {!! l('Prices are exclusive of Ecotax.') !!}
@endif
                  ">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="right" data-container="body" data-html="true" 
                                    data-content="{!! l('If Price ends with &quot;u&quot;, it will be taken as <i>Unit Price</i> (Price for each single unit in the Package).') !!}
                  ">
                        <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                 </a>
        {!! Form::text('price_pack', old('price_pack', 0.0), array('id' => 'price_pack', 'class' => 'form-control')) !!}
    </div>
</div>


               <div class="panel-footer text-right">
                  <a class="btn btn-link" href="{{ URL::to('pricerules') }}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#rule_type').val('pack');this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>
