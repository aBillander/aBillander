
<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('name', l('Currency name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('iso_code', l('ISO Code')) !!}
    {!! Form::text('iso_code', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('iso_code_num', l('ISO Code number')) !!}
    {!! Form::text('iso_code_num', null, array('class' => 'form-control')) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('sign', l('Symbol')) !!}
    {!! Form::text('sign', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-signPlacement">
    	 {!! Form::label('signPlacement', l('Placement'), ['class' => 'control-label']) !!}
         <div>
           <div class="radio-inline">
             <label>
               {!! Form::radio('signPlacement', '0', false, ['id' => 'signPlacement_off']) !!}
               {!! l('Left') !!}
             </label>
           </div>
           <div class="radio-inline">
             <label>
               {!! Form::radio('signPlacement', '1', true, ['id' => 'signPlacement_on']) !!}
               {!! l('Right') !!}
             </label>
           </div>
         </div>
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-blank">
         {!! Form::label('blank', l('Spacing'), ['class' => 'control-label']) !!} 
              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Include a space between symbol and price (e.g. $1,240.15 -> $ 1,240.15)') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
         <div>
           <div class="radio-inline">
             <label>
               {!! Form::radio('blank', '1', false, ['id' => 'blank_on']) !!}
               {!! l('Yes', [], 'layouts') !!}
             </label>
           </div>
           <div class="radio-inline">
             <label>
               {!! Form::radio('blank', '0', true, ['id' => 'blank_off']) !!}
               {!! l('No', [], 'layouts') !!}
             </label>
           </div>
         </div>
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('thousandsSeparator', l('Thousands separator')) !!}
    {!! Form::text('thousandsSeparator', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('decimalSeparator', l('Decimal separator')) !!}
    {!! Form::text('decimalSeparator', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('decimalPlaces', l('Decimal places')) !!}
    {!! Form::text('decimalPlaces', null, array('class' => 'form-control')) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('conversion_rate', l('Exchange rate')) !!} 
             <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Monetary units per :name', ['name' => AbiContext::getContext()->currency->name]) }}" -->
             <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('This rate is to be defined according to your Company\'s default currency. For example, if the default currency is the Euro, and this currency is Dollar, type "1.31", since 1€ usually is worth $1.31 (at the time of this writing). Use the converter here for help: http://www.xe.com/ucc/.') }}">
                    <i class="fa fa-question-circle abi-help"></i>
             </a>
    {!! Form::text('conversion_rate', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
</div>

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
{!! link_to_route('currencies.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
