
<div class="row">
                  <div class="form-group col-lg-7 col-md-7 col-sm-7 {{ $errors->has('name') ? 'has-error' : '' }}">
                     {{ l('Lead Name') }}
                     {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                     {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>

         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('party_id') ? 'has-error' : '' }}">

{{--
@if ($party ?? null)
            {{ Form::hidden('party_id', $party->id, array('id' => 'party_id')) }}
@else
            {{ l('Party') }}
            {!! Form::select('party_id', $leadList, null, array('class' => 'form-control', 'id' => 'party_id')) !!}
            {!! $errors->first('party_id', '<span class="help-block">:message</span>') !!}
@endif
--}}
            {{ l('Party') }}
            {!! Form::select('party_id', (count($partyList) >1 ? ['' => l('-- Please, select --', 'layouts')] : []) + $partyList, null, array('class' => 'form-control', 'id' => 'party_id')) !!}
            {!! $errors->first('party_id', '<span class="help-block">:message</span>') !!}
         </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('status', l('Status', 'layouts')) !!}
            {!! Form::select('status', $statusList, null, array('class' => 'form-control')) !!}
        </div>
</div>

<div class="row">

                  <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('description') ? 'has-error' : '' }}">
                     {{ l('Description') }}
                     {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'rows' => '3')) !!}
                     {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                  </div>
</div>

<div class="row">

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {{ l('Lead Date') }}
        {!! Form::text('lead_date_form', null, array('id' => 'lead_date_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {{ l('Lead End Date') }}
        {!! Form::text('lead_end_date_form', null, array('id' => 'lead_end_date_form', 'class' => 'form-control')) !!}
    </div>

         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('user_assigned_to_id') ? 'has-error' : '' }}">
            {{ l('Assigned to') }}
            {!! Form::select('user_assigned_to_id', $userList, null, array('class' => 'form-control', 'id' => 'user_assigned_to_id')) !!}
            {!! $errors->first('user_assigned_to_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>

  <div class="row">
          <div id="notes_field" name="notes_field" class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
            {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
          </div>
  </div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('leads.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}




@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

        });


    </script> 



{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#lead_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#lead_end_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
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
