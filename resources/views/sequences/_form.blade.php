@php

  $editable = isset($sequence->model_name) ? ['onfocus'=>'this.blur();'] : [] ;

@endphp
<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('name', l('Sequence name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('model_name', l('Document type')) !!}
    {!! Form::select('model_name', array('0' => l('-- Please, select --', [], 'layouts')) + $document_typeList, null, array('class' => 'form-control')+$editable) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('prefix', l('Prefix')) !!}
    {!! Form::text('prefix', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('length', l('Length')) !!} 
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Document id will be left padded with 0\'es to this length') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
    {!! Form::text('length', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('separator', l('Separator')) !!} 
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('To show between document_prefix and document_id') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
    {!! Form::text('separator', null, array('class' => 'form-control')) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('next_id', l('Next ID')) !!}
    {!! Form::text('next_id', null, array('class' => 'form-control')) !!}
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
{!! link_to_route('sequences.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
