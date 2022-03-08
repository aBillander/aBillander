
<div class="row">
    <div class="form-group col-lg-8 col-md-8 col-sm-8">
       {!! Form::label('image', 'Upload Image') !!}
       {!! Form::file('image', null, array('required', 'class'=>'form-control')) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-12 col-md-12 col-sm-12">
        {!! Form::label('caption', l('Caption')) !!}
        {!! Form::text('caption', null, array('class' => 'form-control')) !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('position', l('Position')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use multiples of 10. Use other values to interpolate.', [], 'layouts') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('position', null, array('class' => 'form-control')) !!}
    </div>
    
    <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-is_featured">
     {!! Form::label('is_featured', l('Is Featured?', [], 'layouts'), ['class' => 'control-label']) !!}
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('is_featured', '1', false, ['id' => 'is_featured_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('is_featured', '0', true, ['id' => 'is_featured_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
     </div>
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
	{!! link_to_route('images.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
