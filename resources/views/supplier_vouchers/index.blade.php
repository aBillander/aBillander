@extends('layouts.master')

@section('title') {{ l('Supplier Vouchers') }} @parent @stop


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

        <a href="{{ route('suppliervouchers.export', Request::all()) }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

        <a href="{{ route('supplier.downpayments.index') }}" class="btn xbtn-sm btn-white" 
            title="{{l('Go to', [], 'layouts')}}" style="margin-left: 22px;"><i class="fa fa-money"></i> {{l('Down Payments', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Supplier Vouchers') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'suppliervouchers.index', 'method' => 'GET', 'id' => 'process')) !!}

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

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('autosupplier_name', l('Supplier')) !!}
    {!! Form::text('autosupplier_name', null, array('class' => 'form-control', 'id' => 'autosupplier_name')) !!}

    {!! Form::hidden('supplier_id', null, array('id' => 'supplier_id')) !!}
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
{!! link_to_route('suppliervouchers.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
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
			<th>{{l('Supplier')}}</th>
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
			<td>{{ $payment->id }}
@if( $payment->is_down_payment)
        <a href="{{ URL::to('supplierdownpayments/' . optional(optional($payment->downpaymentdetail)->downpayment)->id . '/edit') }}" class="btn btn-xs alert-danger" title="{{l('Down Payment') }}" target="_blank">&nbsp;<i class="fa fa-money"></i>&nbsp;</a>
@endif
      </td>
			<td>
          <a href="{{ URL::to('supplierinvoices/' . optional($payment->supplierInvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->supplierInvoice->document_reference or '' }}</a></td>
			<td>
          <a href="{{ URL::to('suppliers/' . optional($payment->supplier)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->supplier->name_regular }}</a></td>
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
            @if ( !optional($payment->paymentable->supplier)->bankaccount )
                <i class="fa fa-exclamation-triangle btn-xs alert-danger" title="{{ l('Not a valid Bank Account!') }}"></i>
            @endif


{{-- Alternative add voucher to SEPA direct debit
<!-- Single button -->
<div class="btn-group">
  <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-bank"></i> &nbsp; </a> <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    {!! Form::open(array('route' => 'sepasp.directdebit.add.voucher', 'id' => 'add_voucher_form_'.$payment->id)) !!}
       {!! Form::hidden('voucher_id['. $payment->id .']', $payment->id, array('id' => 'voucher_id_'.$payment->id)) !!}
       <div class="form-group">
         {!! Form::select('sdd_id['. $payment->id .']', $sddList, null, ['class' => 'form-control', 'id' => 'sdd_id_'.$payment->id]) !!}
       </div>
       <!-- div class="form-group">
         {!! Form::text('document_reference', null, ['class' => 'form-control', 'id' => 'document_reference',  'placeholder' => l('Reference')]) !!}
       </div -->
       <div class="form-group">
         <button type="submit" class="btn alert-success">{{l('Add', 'layouts')}}</button>
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Add Voucher to automatic payment remittance.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
       </div>
    {!! Form::close() !!}
  </ul>
</div>
--}}






          @endif
        @else 
          <i class="fa fa-square-o" style="color: #df382c;"></i>
        @endif
      </td -->

            <td class="text-center button-pad">
            	@if     ( $payment->status == 'pending' )
            		<span class="label label-info">
            	@elseif ( $payment->status == 'bounced' )
            		<span class="label label-danger">
              @elseif ( $payment->status == 'paid' )
                <span class="label label-success">
              @elseif ( $payment->status == 'uncollectible' )
                <span class="label alert-danger">
            	@else
            		<span class="label">
            	@endif
            	{{\App\Payment::getStatusName($payment->status)}}</span>

              @if ( $payment->status == 'paid' && !$payment->is_down_payment)
{{--                @if ( \App\Configuration::isTrue('ENABLE_CRAZY_IVAN') ) --}}

                    <a href="{{ route('suppliervoucher.unpay', [$payment->id]) }}" class="btn btn-xs btn-danger" 
                    title="{{l('Undo', 'layouts')}}" xstyle="margin-left: 22px;"><i class="fa fa-undo"></i></a>
               
{{--                @endif --}}
              @endif

              @if ( $payment->status == 'uncollectible' )
                @if ( 1 )

                    <a href="{{ route('suppliervoucher.collectible', [$payment->id]) }}" class="btn btn-xs btn-danger" 
                    title="{{l('Undo', 'layouts')}}" xstyle="margin-left: 22px;"><i class="fa fa-undo"></i></a>
               
                @endif
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

      <td class="text-right button-pad">

              @if (  ( $payment->status == 'paid' ) 
                  || ( $payment->status == 'bounced' ) 
                  || ( $payment->status == 'uncollectible' ) )

                  <a class="btn btn-xs btn-grey" href="{{ URL::to('suppliervouchers/' . $payment->id . '/edit' ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

            	@else

                	<a class="btn btn-sm btn-warning" href="{{ URL::to('suppliervouchers/' . $payment->id . '/edit' ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

      @if (0 && $payment->auto_direct_debit && !$payment->bankorder)

                  <a class="btn btn-sm btn-navy add-voucher-to-sdd" data-id="{{$payment->id}}" data-type=""  title="{{l('Add Voucher to SEPA Direct Debit', 'sepasp')}}" onClick="return false;"><i class="fa fa-bank"></i></a>
      @endif

 
      @if ($payment->bankorder && ($payment->bankorder->status == 'pending'))
      @else
	                <a class="btn btn-sm btn-blue" href="{{ URL::to('suppliervouchers/' . $payment->id  . '/pay' ) }}" title="{{l('Make Payment', 'suppliervouchers')}}"><i class="fa fa-money"></i>
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

@include('layouts/back_to_top_button')

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

{{-- Auto Complete --}}
{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(document).ready(function() {

        $("#autosupplier_name").autocomplete({
            source : "{{ route('supplierinvoices.ajax.supplierLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                supplier_id = value.item.id;

                $("#autosupplier_name").val(value.item.name_regular);
                $("#supplier_id").val(value.item.id);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_regular + "</div>" )
                .appendTo( ul );
            };


    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });


   $('#process').submit(function(event) {

     if ( $("#autosupplier_name").val() == '' ) $('#supplier_id').val('');

     return true;

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


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {

      // Capture the "click" event of the link
      // https://www.bootply.com/WAkbhdKmeb
      $('.dropdown-menu>form').click(function(e){
        e.stopPropagation();
      });

});

</script>

@endsection

@section('styles')    @parent

<style>

  .dropdown-menu form {
    padding:10px;
  }

</style>

@endsection

