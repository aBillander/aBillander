
{!! Form::hidden('back_route', ($back_route ?? ''), array('id' => 'back_route')) !!}

    <div class="row">
          <div class="form-group col-lg-7 col-md-7 col-sm-7 {{ $errors->has('name') ? 'has-error' : '' }}">
             {{ l('Action Name', 'actions') }}
             {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
             {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
          </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('priority', l('Priority', 'actions')) !!}
            {!! Form::select('priority', $priorityList, null, array('class' => 'form-control')) !!}
        </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('status', l('Status', 'layouts')) !!}
            {!! Form::select('status', $statusList, null, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="row">

          <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('description') ? 'has-error' : '' }}">
             {{ l('Description', 'actions') }}
             {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'rows' => '3')) !!}
             {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
          </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('action_type_id', l('Action type', 'actions')) !!}
            {!! Form::select('action_type_id', $action_typeList, null, array('class' => 'form-control')) !!}
        </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('contact_id', l('Contact', 'actions')) !!}
            {!! Form::select('contact_id', ['' => l('-- Please, select --', [], 'layouts')] + $customer_contactList, null, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="row">

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {{ l('Start Date', 'actions') }}
        {!! Form::text('start_date_form', null, array('id' => 'start_date_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {{ l('Due Date', 'actions') }}
        {!! Form::text('due_date_form', null, array('id' => 'due_date_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {{ l('Finish Date', 'actions') }}
        {!! Form::text('finish_date_form', null, array('id' => 'finish_date_form', 'class' => 'form-control')) !!}
    </div>

         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('sales_rep_id') ? 'has-error' : '' }}">
            {!! Form::label('sales_rep_id', l('Assigned to', 'actions')) !!}
            {!! Form::select('sales_rep_id', ['' => l('-- Please, select --', [], 'layouts')] + $salesrepList, null, array('class' => 'form-control', 'id' => 'sales_rep_id')) !!}
            {!! $errors->first('sales_rep_id', '<span class="help-block">:message</span>') !!}
         </div>

    </div>

    <div class="row">
          <div id="notes_field" name="notes_field" class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('results') ? 'has-error' : '' }}">
            {{ l('Results', 'actions') }}
            {!! Form::textarea('results', null, array('class' => 'form-control', 'id' => 'results', 'rows' => '3')) !!}
            {!! $errors->first('results', '<span class="help-block">:message</span>') !!}
          </div>
    </div>



		{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
        {!! link_to( ($back_route != '' ? $back_route : 'contacts.index'), l('Cancel', [], 'layouts'), array('class' => 'btn btn-warning')) !!}


@section('scripts')  @parent 

    <script type="text/javascript">

        $(document).ready(function() {

        });


    </script>



{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#start_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#due_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#finish_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

</script>

@endsection


@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection
