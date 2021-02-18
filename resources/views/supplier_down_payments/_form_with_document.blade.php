
<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('reference') ? 'has-error' : '' }}">
        {!! Form::label('reference', l('Reference')) !!}
        {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
        {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', l('Description')) !!}
        {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="row">

    <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('due_date') ? 'has-error' : '' }}">
        {!! Form::label('due_date_form', l('Due Date')) !!}
        {!! Form::text('due_date_form', null, array('id' => 'due_date_form', 'class' => 'form-control')) !!}
        {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
    </div>

@if ( isset($cheque) && ($cheque->status == 'paid') )
@php
          $statusList = ['paid' => $statusList['paid']];
@endphp
@endif
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('status', l('Status', 'layouts')) !!}
    {!! Form::select('status', $statusList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-3 col-md-3 col-sm-3">
    {!! Form::label('payment_type_id', l('Payment Type')) !!}
    {!! Form::select('payment_type_id', $payment_typeList, null, array('class' => 'form-control')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('amount') ? 'has-error' : '' }}">
    {!! Form::label('amount', l('Amount')) !!}
    {!! Form::text('amount', null, array('id' => 'amount', 'class' => 'form-control')) !!}
    {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
</div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
            {!! Form::label('currency_id', l('Currency'), ['class' => 'control-label']) !!}
            {!! Form::select('currency_id', $currencyList, old('currency_id', \App\Configuration::getInt('DEF_CURRENCY')), array('class' => 'form-control', 'id' => 'currency_id')) !!}
            {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>

<div class="row">

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('drawee_bank_id') ? 'has-error' : '' }}">
            {!! Form::label('drawee_bank_id', l('Drawee Bank'), ['class' => 'control-label']) !!}
            {!! Form::select('drawee_bank_id', ['' => l('-- Please, select --', [], 'layouts')] + $bankList, null, array('class' => 'form-control', 'id' => 'drawee_bank_id')) !!}
            {!! $errors->first('drawee_bank_id', '<span class="help-block">:message</span>') !!}
         </div>

    <div class="form-group col-lg-7 col-md-7 col-sm-7 {{ $errors->has('notes') ? 'has-error' : '' }}">
       {!! Form::label('notes', l('Notes', [], 'layouts')) !!}
       {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
       {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
    </div>
</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('supplier.downpayments.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}




@section('styles')    @parent

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
  .ui-datepicker{ z-index: 9999 !important;}


/* Undeliver dropdown effect */
   .hover-item:hover {
      background-color: #d3d3d3 !important;
    }
</style>

@endsection


@section('scripts')    @parent


{{-- Auto Complete --}}
{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script type="text/javascript">

    $(document).ready(function() {


    $( "#due_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    });

</script> 

@endsection
