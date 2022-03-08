
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('data_file') ? 'has-error' : '' }}">
       {!! Form::label('data_file', l('Upload File', [], 'layouts')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('First row should be the header row.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>

            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-blue">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="data_file" id="data_file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>

    </div>

     <div class="form-group col-lg-1 col-md-1 col-sm-1">
     </div>

</div>
<div class="row">

     <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('images_folder') ? 'has-error' : '' }}">
       {!! Form::label('images_folder', l('Images Folder'), ['class' => 'control-label']) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Image Files are located in this Server Folder.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
       {!! Form::text('images_folder', old('images_folder', $images_folder), array('class' => 'form-control', 'id' => 'images_folder')) !!}
      </div>

</div>
<div class="row">
    
    <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-has_header" style="display: none">
     {!! Form::label('has_header', l('File contains header row?'), ['class' => 'control-label']) !!}
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('has_header', '1', true, ['id' => 'has_header_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('has_header', '0', false, ['id' => 'has_header_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
     </div>
    </div>

</div>
