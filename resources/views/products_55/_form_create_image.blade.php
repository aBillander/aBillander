
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
       {!! Form::label('image', l('Upload Image')) !!}
       {{-- Form::file('image', null, array('required', 'class'=>'form-control')) --}}

            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="image" id="image" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>

    </div>
</div>
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('caption', l('Caption')) !!}
        {!! Form::text('caption', null, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('position', l('Position', [], 'layouts')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Use multiples of 10. Use other values to interpolate.', [], 'layouts') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('position', 0, array('class' => 'form-control')) !!}
    </div>
    
    <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-is_featured">
     {!! Form::label('is_featured', l('Is Featured?'), ['class' => 'control-label']) !!}
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

</div>
