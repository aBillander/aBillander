
<div class="row">
<div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('name') ? 'has-error' : '' }}">
    {!! Form::label('name', l('Todo name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-completed">
 {!! Form::label('completed', l('Completed?'), ['class' => 'control-label']) !!}
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('completed', '1', false, ['id' => 'completed_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('completed', '0', true, ['id' => 'completed_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>

</div>


<div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('description') ? 'has-error' : '' }}">
                     {{ l('Description') }}
                     {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'rows' => '5')) !!}
                     {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                  </div>

</div>


<div class="row">

        <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('url') ? 'has-error' : '' }}">
            {!! Form::label('url', l('Url')) !!}
            {!! Form::text('url', null, array('class' => 'form-control')) !!}
            {!! $errors->first('url', '<span class="help-block">:message</span>') !!}
        </div>

         <div class="col-lg-3 col-md-3 col-sm-3 {{ $errors->has('due_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Due date') }}
               {!! Form::text('due_date', null, array('class' => 'form-control', 'id' => 'due_date', 'autocomplete' => 'off')) !!}
               {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

</div>


<div class="row">

</div>


<div class="row">

</div>


{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('todos.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}





@section('scripts') @parent 

{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#due_date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection




@section('styles') @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection