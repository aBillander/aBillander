
<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('document_date', l('Date')) !!}
        {!! Form::text('document_date', $date, array('id' => 'document_date', 'xreadonly' => 'xreadonly', 'class' => 'form-control')) !!}
    </div>
     <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('name') ? 'has-error' : '' }}">
        {{ l('Name') }}
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
     </div>
</div>

<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4">
        {!! Form::label('warehouse_id', l('Warehouse')) !!}
        {!! Form::select('warehouse_id', $warehouseList, AbiConfiguration::get('DEF_WAREHOUSE'), array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6" id="div-initial_inventory">
     {!! Form::label('initial_inventory', l('Initial Inventory?'), ['class' => 'control-label']) !!}
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('initial_inventory', '1', false, ['id' => 'initial_inventory_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('initial_inventory', '0', true, ['id' => 'initial_inventory_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
     </div>
    </div>
</div>
        
<div class="row">
      <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
         {!! Form::label('notes', l('Notes', [], 'layouts')) !!}
         {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
         {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
      </div>
</div>

  {!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
  {!! link_to_route('stockcounts.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}



@section('scripts')
@parent

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#document_date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
</script>

@endsection

@section('styles')
@parent

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

@endsection
