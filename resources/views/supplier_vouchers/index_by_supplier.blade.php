@extends('layouts.master')

@section('title') {{ l('Supplier Vouchers') }} @parent @endsection


@section('content')

<div class="page-header">
    <!-- div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('suppliervouchers/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div -->
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ route('sepasp.directdebits.index') }}" class=" hide  btn xbtn-sm btn-navy" 
        		title="{{l('Go to', [], 'layouts')}}" style="margin-left: 22px;"><i class="fa fa-bank"></i> {{l('SEPA Direct Debits', 'sepasp')}}</a>
    </div>
    <h2>
        {{ l('Supplier Vouchers') }} <span class="lead well well-sm">

                  <a href="{{ URL::to('suppliers/' . $supplier->id . '/edit') }}" title=" {{l('View Supplier')}} " target="_blank">{{ $supplier->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address', 'supplierinvoices')}}" data-content="
                                  {{$supplier->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$supplier->identification}}<br />
                                  {{ $supplier->address->address1 }} {{ $supplier->address->address2 }}<br />
                                  {{ $supplier->address->postcode }} {{ $supplier->address->city }}, {{ $supplier->address->state->name }}<br />
                                  {{ $supplier->address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
                 @if($supplier->sales_equalization)
                  <span id="sales_equalization_badge" class="badge" title="{{l('Equalization Tax')}}"> RE </span>
                 @endif
                 </span>
                   &nbsp; 
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $supplier->currency->name }}">{{ $supplier->currency->iso_code }}</span>
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => ['supplier.vouchers', $supplier->id], 'method' => 'GET')) !!}

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
{{--
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
--}}
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
{!! link_to_route('supplier.vouchers', l('Reset', [], 'layouts'), [$supplier->id], array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
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
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Invoice')}}</th>
			<!-- th>{{l('Supplier')}}</th -->
			<th>{{l('Subject')}}</th>
			<th>{{l('Due Date')}}</th>
			<th>{{l('Payment Date')}}</th>
			<th class="text-right">{{l('Amount')}}</th>
      <th style="text-transform: none;">{{l('Payment Type', 'suppliervouchers')}}</th>
      <!-- th style="text-transform: none;">{{l('Auto Direct Debit', 'suppliervouchers')}}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Include in automatic payment remittances', 'suppliervouchers') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
          </th -->
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($payments as $payment)
		<tr>
			<td>{{ $payment->id }}</td>
			<td>
          <a href="{{ URL::to('supplierinvoices/' . optional($payment->supplierInvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->supplierInvoice->document_reference ?? '' }}</a></td>
			<!-- td>
          <a href="{{ URL::to('suppliers/' . optional(optional($payment->supplierInvoice)->supplier)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->supplierInvoice->supplier->name_regular ?? '' }}</a></td -->
			<td>{{ $payment->name }}</td>
			<td @if ( !$payment->payment_date AND $payment->is_overdue ) ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<td>{{ abi_date_short($payment->payment_date) }}</td>
			<td class="text-right">{{ $payment->as_money_amount('amount') }}

@if ( $payment->currency_conversion_rate != 1.0 )
        <br />
        <span class="text-warning">{{ \App\Currency::viewMoneyWithSign($payment->amount_currency, $payment->currency) }}</span>

@endif

            </td>

      <td>{{ optional($payment->paymenttype)->name }}</td>

      <!-- td class="text-center">
        @if ($payment->auto_direct_debit) 
          @if ($payment->bankorder)
            <a class="btn btn-xs btn-grey" href={{ route('sepasp.directdebits.show', $payment->bankorder->id) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-bank"></i>
                      <span xclass="label label-default">{{ $payment->bankorder->document_reference }}</span>
                    </a>
          @else
            <i class="fa fa-check-square xbtn-xs xalert-success" style="color: #38b44a;"></i>
            @if ( !$payment->paymentable->supplier->bankaccount )
                <i class="fa fa-exclamation-triangle btn-xs alert-danger" title="{{ l('Not a valid Bank Account!') }}"></i>
            @endif
          @endif
        @else 
          <i class="fa fa-square-o" style="color: #df382c;"></i>
        @endif
      </td -->

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
            	{{\App\Payment::getStatusName($payment->status)}}</span></td>


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

                	<a class="btn btn-sm btn-warning" href="{{ URL::to('suppliervouchers/' . $payment->id . '/edit?back_route=' . urlencode('suppliervouchers/suppliers/' . $supplier->id ) ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
 
      @if ($payment->bankorder && ($payment->bankorder->status == 'pending'))
      @else
	                <a class="btn btn-sm btn-blue" href="{{ URL::to('suppliervouchers/' . $payment->id  . '/pay?back_route=' . urlencode('suppliervouchers/suppliers/' . $supplier->id ) ) }}" title="{{l('Make Payment', 'suppliervouchers')}}"><i class="fa fa-money"></i>
	                </a>
      @endif

	                @if(0 && $payment->amount==0.0)
 
      @if ($payment->bankorder && ($payment->bankorder->status != 'pending'))
      @else
	                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
	                    href="{{ URL::to('suppliervouchers/' . $payment->id ) }}" 
	                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
	                    data-title="{{ l('Supplier Voucher', 'suppliervouchers') }} :: {{ l('Invoice') }}: {{ $payment->paymentable->document_reference }} . {{ l('Due Date') }}: {{ $payment->due_date }}" 
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

@endsection

@include('layouts/modal_delete')

@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

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
