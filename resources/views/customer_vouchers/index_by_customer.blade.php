@extends('layouts.master')

@section('title') {{ l('Customer Vouchers') }} @parent @stop


@section('content')

<div class="page-header">
    <!-- div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customervouchers/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div -->
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <button name="b_pay_multiple" id="b_pay_multiple" class="btn btn-sm btn-blue" htype="button" title="{{l('Pay multiple Vouchers at once')}}"><i class="fa fa-money"></i>
           &nbsp; {{l('Pay multiple')}}
        </button>

        <a href="{{ route('customervouchers.export', ['customer_id' => $customer->id, 'autocustomer_name' => $customer->name_regular] + Request::all()) }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

        <a href="{{ route('sepasp.directdebits.index') }}" class="btn xbtn-sm btn-navy" 
        		title="{{l('Go to', [], 'layouts')}}" style="margin-left: 22px;"><i class="fa fa-bank"></i> {{l('SEPA Direct Debits', 'sepasp')}}</a>
    </div>
    <h2>
        {{ l('Customer Vouchers') }} <span class="lead well well-sm">

                  <a href="{{ URL::to('customers/' . $customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{{ $customer->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address', 'customerinvoices')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$customer->identification}}<br />
                                  {{ $customer->address->address1 }} {{ $customer->address->address2 }}<br />
                                  {{ $customer->address->postcode }} {{ $customer->address->city }}, {{ $customer->address->state->name }}<br />
                                  {{ $customer->address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
                 @if($customer->sales_equalization)
                  <span id="sales_equalization_badge" class="badge" title="{{l('Equalization Tax')}}"> RE </span>
                 @endif
                 </span>
                   &nbsp; 
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $customer->currency->name }}">{{ $customer->currency->iso_code }}</span>
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => ['customer.vouchers', $customer->id], 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_from_form', l('Date from', 'layouts')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_to_form', l('Date to', 'layouts')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>


    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('amount', l('Amount')) !!}
        {!! Form::text('amount', null, array('id' => 'amount', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('status', l('Status')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-auto_direct_debit">
     {!! Form::label('auto_direct_debit', l('Auto Direct Debit'), ['class' => 'control-label']) !!}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                        data-content="{{ l('Include in automatic payment remittances') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                   </a>
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('auto_direct_debit', '1', false, ['id' => 'auto_direct_debit_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('auto_direct_debit', '0', false, ['id' => 'auto_direct_debit_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('auto_direct_debit', '-1', true, ['id' => 'auto_direct_debit_all']) !!}
           {!! l('All', [], 'layouts') !!}
         </label>
       </div>
     </div>
</div>

{{--
<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('warehouse_id', l('Warehouse')) !!}
    {!! Form::select('warehouse_id', array('0' => l('All', [], 'layouts')) + $warehouseList, null, array('class' => 'form-control')) !!}
</div>
--}}

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('customer.vouchers', l('Reset', [], 'layouts'), [$customer->id], array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>



{!! Form::open( ['method' => 'POST', 'id' => 'form-select-payments'] ) !!}

@php
  $display = $errors->has('bulk_payment_date') || $errors->has('bulk_payment_type_id');
@endphp


<div name="pay_multiple" id="pay_multiple" 
@if ( !$display )
     style="display:none"
@endif
>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Pay multiple Vouchers at once') }}</h3></div>
            <div class="panel-body">



<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('bulk_payment_date') ? 'has-error' : '' }}">
        {!! Form::label('bulk_payment_date_form', l('Payment Date')) !!}
        {!! Form::text('bulk_payment_date_form', null, array('id' => 'bulk_payment_date_form', 'class' => 'form-control')) !!}
        {!! $errors->first('bulk_payment_date', '<span class="help-block">:message</span>') !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('bulk_payment_type_id') ? 'has-error' : '' }}">
    {!! Form::label('bulk_payment_type_id', l('Payment Type')) !!}
    {!! Form::select('bulk_payment_type_id', array('' => l('-- Please, select --', [], 'layouts')) + $payment_typeList, null, array('class' => 'form-control')) !!}
    {!! $errors->first('bulk_payment_type_id', '<span class="help-block">:message</span>') !!}
</div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('group_balance', l('Amount')) !!}
        <div id="group_balance" class="form-control alert-warning">0.0</div>
    </div>


<div class="form-group col-lg-3 col-md-3 col-sm-3" style="padding-top: 22px">
{!! Form::submit(l('Pay multiple'), array('class' => 'btn btn-success',  'id' => 'form-select-paymentsSubmit')) !!}
{!! link_to_route('customer.vouchers', l('Reset', [], 'layouts'), [$customer->id], array('class' => 'btn btn-warning')) !!}
</div>

</div>

            </div>
        </div>
    </div>
</div>
</div>



<div id="div_payments">
   <div class="table-responsive">

@if ($payments->count())
<table id="payments" class="table table-hover">
	<thead>
		<tr>
      <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll', 'onchange' => 'calculateSelectedAmount()']) !!}</th>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Invoice')}}</th>
      <th>{{l('Invoice Date')}}</th>
			<!-- th>{{l('Customer')}}</th -->
			<th>{{l('Subject')}}</th>
			<th>{{l('Due Date')}}</th>
			<th>{{l('Payment Date')}}</th>
			<th class="text-right">{{l('Amount')}}</th>
      <th style="text-transform: none;">{{l('Payment Type', 'customervouchers')}}</th>
      <th style="text-transform: none;">{{l('Auto Direct Debit', 'customervouchers')}}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Include in automatic payment remittances', 'customervouchers') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
          </th>
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody id="payment_lines">
	@foreach ($payments as $payment)
		<tr>
      <td class="text-center warning">{!! Form::checkbox('payment_group[]', $payment->id, false, ['class' => 'case xcheckbox', 'onchange' => 'calculateSelectedAmount()']) !!}</td>
			<td>{{ $payment->id }}</td>
			<td>
          <a href="{{ URL::to('customerinvoices/' . optional($payment->customerinvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerinvoice->document_reference or '' }}</a></td>
      <td>{{ abi_date_short(optional($payment->customerinvoice)->document_date) }}</td>
			<!-- td>
          <a href="{{ URL::to('customers/' . optional(optional($payment->customerinvoice)->customer)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerinvoice->customer->name_regular or '' }}</a></td -->
			<td>{{ $payment->name }}</td>
			<td @if ( !$payment->payment_date AND $payment->is_overdue ) ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<td>{{ abi_date_short($payment->payment_date) }}</td>
			<td class="text-right">{{ $payment->as_money_amount('amount') }}

              <input name="pay_amount[{{ $payment->id }}]" id="pay_amount[{{ $payment->id }}]" class=" hide  selectedamount form-control input-sm" type="text" size="3" maxlength="5" style="min-width: 0; xwidth: auto; display: inline;" value="{{ $payment->as_priceable($payment->amount, $payment->currency) }}" onclick="this.select()" onkeyup="calculateSelectedAmount()">

      </td>

      <td>{{ optional($payment->paymenttype)->name }} 

@if( ($payment->payment_type_id == \App\Configuration::getInt('DEF_CHEQUE_PAYMENT_TYPE')) && $payment->chequedetail )

              <a class="btn btn-xs btn-warning" href="{{ URL::to('cheques/' . $payment->chequedetail->cheque_id . '/edit' ) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-external-link"></i></a>

@endif
      </td>

      <td class="text-center">
        @if ($payment->auto_direct_debit) 
          @if ($payment->bankorder)
            <a class="btn btn-xs btn-grey" href={{ route('sepasp.directdebits.show', $payment->bankorder->id) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-bank"></i>
                      <span xclass="label label-default">{{ $payment->bankorder->document_reference }}</span>
                    </a>
          @else
            <i class="fa fa-check-square xbtn-xs xalert-success" style="color: #38b44a;"></i>
            @if ( !$payment->paymentable->customer->bankaccount )
                <i class="fa fa-exclamation-triangle btn-xs alert-danger" title="{{ l('Not a valid Bank Account!') }}"></i>
            @endif
          @endif
        @else 
          <i class="fa fa-square-o" style="color: #df382c;"></i>
        @endif
      </td>

            <td class="text-center">
            	@if     ( $payment->status == 'pending' )
            		<span class="label label-info">
            	@elseif ( $payment->status == 'bounced' )
            		<span class="label label-danger">
            	@elseif ( $payment->status == 'paid' )
            		<span class="label label-success">
            	@else
            		<span>
            	@endif
            	{{\App\Payment::getStatusName($payment->status)}}</span>

              @if ( $payment->status == 'paid' )
{{--                @if ( \App\Configuration::isTrue('ENABLE_CRAZY_IVAN') ) --}}

                    <a href="{{ route('customervoucher.unpay', [$payment->id]) }}" class="btn btn-xs btn-danger" 
                    title="{{l('Undo Payment')}}" xstyle="margin-left: 22px;"><i class="fa fa-undo"></i></a>
               
{{--                @endif --}}
              @endif

            </td>


      <td class="text-center">
          @if ($payment->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $payment->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif
      </td>


			<td class="text-right">
              @if ( ( $payment->status == 'paid' ) || ( $payment->status == 'bounced' ) )

            	@else

                	<a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id . '/edit?back_route=' . urlencode('customervouchers/customers/' . $customer->id ) ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
 
      @if ($payment->bankorder && ($payment->bankorder->status == 'pending'))
      @else
	                <a class="btn btn-sm btn-blue" href="{{ URL::to('customervouchers/' . $payment->id  . '/pay?back_route=' . urlencode('customervouchers/customers/' . $customer->id ) ) }}" title="{{l('Make Payment', 'customervouchers')}}"><i class="fa fa-money"></i>
	                </a>
      @endif

	                @if(0 && $payment->amount==0.0)
 
      @if ($payment->bankorder && ($payment->bankorder->status != 'pending'))
      @else
	                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
	                    href="{{ URL::to('customervouchers/' . $payment->id ) }}" 
	                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
	                    data-title="{{ l('Customer Voucher', 'customervouchers') }} :: {{ l('Invoice') }}: {{ $payment->paymentable->document_reference }} . {{ l('Due Date') }}: {{ $payment->due_date }}" 
	                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
      @endif

	                @endif

            	@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
{!! $payments->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $payments->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

{!! Form::close() !!}


@stop

@include('layouts/modal_delete')

@section('scripts') @parent 

<script type="text/javascript">

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery

$(function () {
    var $tblChkBox = $("#payment_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});

$("#payment_lines").on("change", function () {
    if (!$(this).prop("checked")) {
        $("#ckbCheckAll").prop("checked", false);
    }
});

// check box selection ENDS -->



$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });

   $("#b_pay_multiple").click(function() {
      $('#pay_multiple').show();
   });


   calculateSelectedAmount();


   $("#form-select-paymentsSubmit").click(function() {
      // this.disabled=true;
      $('#form-select-payments').attr('action', '{{ route( 'customervouchers.bulk.pay' )}}');
      $('#form-select-payments').submit();
      return false;
   });
});



        function calculateSelectedAmount() {
            var total = 0;
            $('.xcheckbox:checked').each(function(index,value){

                total += parseFloat($(this).closest('tr').find('.selectedamount').val().replace(',', '.'));

            });

            $('#group_balance').html(currencyFormat{{ $customer->currency->iso_code }}(total));
        }


        function currencyFormatUSD(num) {
          return '$' + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        // console.info(currencyFormat(2665)) // $2,665.00
        // console.info(currencyFormat(102665)) // $102,665.00


        function currencyFormatEUR(num) {
          return (
            num
              .toFixed(2) // always two decimal digits
              .replace('.', ',') // replace decimal point character with ,
              .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ' €'
          ) // use . as a separator
        }

        // console.info(currencyFormatDE(1234567.89)) // output 1.234.567,89 €



</script>

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#bulk_payment_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
</script>

@endsection

@section('styles')    @parent

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

@endsection
