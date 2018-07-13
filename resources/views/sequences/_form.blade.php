@php

  $editable = isset($sequence->model_name) ? ['onfocus'=>'this.blur();'] : [] ;

@endphp
<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
    {!! Form::label('name', l('Sequence name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('model_name') ? 'has-error' : '' }}">
    {!! Form::label('model_name', l('Document type')) !!}
    {!! Form::select('model_name', array('0' => l('-- Please, select --', [], 'layouts')) + $document_typeList, null, array('class' => 'form-control')+$editable) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('prefix') ? 'has-error' : '' }}">
    {!! Form::label('prefix', l('Prefix')) !!}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Should be empty if Separator is empty') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
    {!! Form::text('prefix', null, array('class' => 'form-control', 'onkeyup' => 'show_sequence_format()', 'onchange' => 'show_sequence_format()')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('length') ? 'has-error' : '' }}">
    {!! Form::label('length', l('Length')) !!} 
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Document id will be left padded with 0\'es to this length') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
    {!! Form::text('length', null, array('class' => 'form-control', 'onkeyup' => 'show_sequence_format()', 'onchange' => 'show_sequence_format()')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('separator', l('Separator')) !!} 
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('To show between Document Prefix and Document id') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
    {!! Form::text('separator', null, array('class' => 'form-control', 'onkeyup' => 'show_sequence_format()', 'onchange' => 'show_sequence_format()')) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('next_id') ? 'has-error' : '' }}">
    {!! Form::label('next_id', l('Next ID')) !!}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('A new Document on this Sequence will be assigned this id. This field is automatically incremented with a new Document.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
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




@section('scripts')    @parent

    <script type="text/javascript">

        $(document).ready(function() {

          show_sequence_format();

        });

        function show_sequence_format()
        {
          var format;

          format = $('#prefix').val() + $('#separator').val() + repeatStringNumTimes("X", $('#length').val());

          $('#sequence_format').html(format);
        }

        function repeatStringNumTimes(string, times) {
      return times > 0 ? string.repeat(times) : "";
    }

    // repeatStringNumTimes("abc", 3);

    </script> 

@endsection
