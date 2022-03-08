
<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        <img src="{{ URL::to(\App\Image::pathProducts() . $image->getImageFolder() . $image->id . '-small_default' . '.' . $image->extension) }}" style="border: 1px solid #dddddd;">
    </div>

    <div class="form-group col-lg-8 col-md-8 col-sm-8">
        {!! Form::label('caption', l('Caption', [], 'products')) !!}
        {!! Form::text('caption', null, array('class' => 'form-control')) !!}
    </div>

</div>
<div class="row">

    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('position', l('Position', [], 'layouts')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Use multiples of 10. Use other values to interpolate.', [], 'layouts') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('position', null, array('class' => 'form-control')) !!}
    </div>
    
    <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-is_featured">
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

  {!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
  {!! link_to_route('products.edit', l('Cancel', [], 'layouts'), array($product->id,'#images'), array('class' => 'btn btn-warning')) !!}
  