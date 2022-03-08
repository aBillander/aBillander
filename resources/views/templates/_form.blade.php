@php

  $editable = isset($template->model_name) ? ['onfocus'=>'this.blur();'] : [] ;

@endphp
<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('name', l('Template name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('model_name', l('Apply to Model')) !!}
    {!! Form::select('model_name', array('0' => l('-- Please, select --', [], 'layouts')) + $templateable_document_typeList, null, array('class' => 'form-control')+$editable) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('folder', l('Folder')) !!}
    {!! Form::text('folder', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('file_name', l('File name')) !!}
    {!! Form::text('file_name', null, array('class' => 'form-control')) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('paper', l('Paper')) !!}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('A4, Letter, etc.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
    {!! Form::text('paper', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('orientation', l('Orientation')) !!}
    {!! Form::select('orientation', array( 
                      'portrait'  => l('Portrait',  [], 'appmultilang'),
                      'landscape' => l('Landscape', [], 'appmultilang')
                  ), null, array('class' => 'form-control')) !!}
</div>
</div>


{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('templates.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
