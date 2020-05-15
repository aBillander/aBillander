
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('data_file') ? 'has-error' : '' }}">
       {!! Form::label('data_file', l('Upload File', [], 'layouts')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('First row should be the header row.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
       {{-- Form::file('image', null, array('required', 'class'=>'form-control')) --}}

            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-blue">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="data_file" id="data_file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>

    </div>

     <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-truncate">
       {!! Form::label('truncate', l('Delete existing Products?'), ['class' => 'control-label']) !!}
       <div>
         <div class="radio-inline">
           <label>
             {!! Form::radio('truncate', '1', false, ['id' => 'truncate_on']) !!}
             {!! l('Yes', [], 'layouts') !!}
           </label>
         </div>
         <div class="radio-inline">
           <label>
             {!! Form::radio('truncate', '0', true, ['id' => 'truncate_off']) !!}
             {!! l('No', [], 'layouts') !!}
           </label>
         </div>
       </div>
      </div>

     <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-simulate">
       {!! Form::label('simulate', l('Simulation Mode?'), ['class' => 'control-label']) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Simulation Mode: errors will be shown, but database will not be changed.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
       <div>
         <div class="radio-inline">
           <label>
             {!! Form::radio('simulate', '1', false, ['id' => 'simulate_on']) !!}
             {!! l('Yes', [], 'layouts') !!}
           </label>
         </div>
         <div class="radio-inline">
           <label>
             {!! Form::radio('simulate', '0', true, ['id' => 'simulate_off']) !!}
             {!! l('No', [], 'layouts') !!}
           </label>
         </div>
       </div>
      </div>

</div>
<div class="row">

     <div class="form-group col-lg-6 col-md-6 col-sm-6" id="div-import_action">
       {!! Form::label('import_action', l('Action'), ['class' => 'control-label']) !!}
       <div>
         <div class="radio-inline" style="margin-left: 10px;">
           <label style="font-weight: normal !important;">
             {!! Form::radio('import_action', '1', false, ['id' => 'import_action_on']) !!}
             {!! l('Create Products') !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                        data-content="{{ l('Import File MUST contain ALL mandatory columns.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
           </label>
         </div>
         <div class="radio-inline">
           <label style="font-weight: normal !important;">
             {!! Form::radio('import_action', '0', true, ['id' => 'import_action_off']) !!}
             {!! l('Update existing Prodcuts') !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ l('Import File can contain columns to be modified only.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
           </label>
         </div>
       </div>
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
