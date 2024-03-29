@extends('layouts.master')

@section('title') {{ l('Customer Vouchers') }} @parent @endsection


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

        <a href="{{ route('customervouchers.export', Request::all()) }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

        <a href="{{ route('cheques.index') }}" class="btn xbtn-sm btn-white" 
            title="{{l('Go to', [], 'layouts')}}" style="margin-left: 22px;"><i class="fa fa-money"></i> {{l('Cheques', [], 'layouts')}}</a>

        <a href="{{ route('sepasp.directdebits.index') }}" class="btn xbtn-sm btn-navy" 
            title="{{l('Go to', [], 'layouts')}}" style="margin-left: 22px;"><i class="fa fa-bank"></i> {{l('SEPA Direct Debits', 'sepasp')}}</a>
    </div>
    <h2>
        {{ l('Customer Vouchers') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'customervouchers.index', 'method' => 'GET', 'id' => 'process')) !!}

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
    {!! Form::label('autocustomer_name', l('Customer')) !!}
    {!! Form::text('autocustomer_name', null, array('class' => 'form-control', 'id' => 'autocustomer_name')) !!}

    {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
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
{!! link_to_route('customervouchers.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

<div class="row">

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('payment_type_id', l('Payment Type')) !!}
    {!! Form::select('payment_type_id', array('' => l('All', [], 'layouts')) + $payment_typeList, null, array('class' => 'form-control')) !!}
</div>


    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_document_reference', l('Invoice')) !!}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                        data-content="{{ l('Full Document Reference or a part of it') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                   </a>
        {!! Form::text('customer_document_reference', null, array('id' => 'customer_document_reference', 'class' => 'form-control')) !!}
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
      <th>{{l('Invoice Date')}}</th>
			<th>{{l('Customer')}}</th>
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
	<tbody>
	@foreach ($payments as $payment)
		<tr>
			<td>{{ $payment->id }}</td>
			<td>
          <a href="{{ URL::to('customerinvoices/' . optional($payment->customerinvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerinvoice->document_reference ?? '' }}</a></td>
      <td>{{ abi_date_short(optional($payment->customerinvoice)->document_date) }}</td>
			<td>
          <a href="{{ URL::to('customers/' . optional(optional($payment->customerinvoice)->customer)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerinvoice->customer->name_regular ?? '' }}</a></td>
			<td>{{ $payment->name }}</td>
			<td @if ( !$payment->payment_date AND $payment->is_overdue ) ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<td>{{ abi_date_short($payment->payment_date) }}</td>
			<td class="text-right">{{ $payment->as_money_amount('amount') }}</td>

      <td class="button-pad">{{ optional($payment->paymenttype)->name }} 

@if( ($payment->payment_type_id == AbiConfiguration::getInt('DEF_CHEQUE_PAYMENT_TYPE')) )
  @if( $payment->chequedetail )

              <a class="btn btn-xs btn-warning" href="{{ URL::to('cheques/' . $payment->chequedetail->cheque_id . '/edit' ) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-external-link"></i></a>

  @else
              <i class="fa fa-exclamation-triangle btn-xs alert-danger" title="{{ l('Pending to receive') }}"></i>
  @endif
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
      </td>

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
            	{{\App\Models\Payment::getStatusName($payment->status)}}</span>

              @if ( $payment->status == 'paid' )
{{--                @if ( AbiConfiguration::isTrue('ENABLE_CRAZY_IVAN') ) --}}

                    <a href="{{ route('customervoucher.unpay', [$payment->id]) }}" class="btn btn-xs btn-danger" 
                    title="{{l('Undo Payment')}}" xstyle="margin-left: 22px;"><i class="fa fa-undo"></i></a>
               
{{--                @endif --}}
              @endif

              @if ( $payment->status == 'uncollectible' )
                @if ( 1 )

                    <a href="{{ route('customervoucher.collectible', [$payment->id]) }}" class="btn btn-xs btn-danger" 
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

                  <a class="btn btn-xs btn-grey" href="{{ URL::to('customervouchers/' . $payment->id . '/edit' ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

            	@else

                	<a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id . '/edit' ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

      @if ($payment->auto_direct_debit && !$payment->bankorder)

                  <a class="btn btn-sm btn-navy add-voucher-to-sdd" data-id="{{$payment->id}}" data-type=""  title="{{l('Add Voucher to SEPA Direct Debit', 'sepasp')}}" onClick="return false;"><i class="fa fa-bank"></i></a>
      @endif

 
      @if ($payment->bankorder && ($payment->bankorder->status == 'pending'))
      @else
	                <a class="btn btn-sm btn-blue" href="{{ URL::to('customervouchers/' . $payment->id  . '/pay' ) }}" title="{{l('Make Payment', 'customervouchers')}}"><i class="fa fa-money"></i>
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

@include('layouts/back_to_top_button')

@endsection

@include('sepa_es::customer_vouchers._modal_add_voucher')

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
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(document).ready(function() {

        $("#autocustomer_name").autocomplete({
            source : "{{ route('home.searchcustomer') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customer_id = value.item.id;

                $("#autocustomer_name").val(value.item.name_regular);
                $("#customer_id").val(value.item.id);

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
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });


    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });


   $('#process').submit(function(event) {

     if ( $("#autocustomer_name").val() == '' ) $('#customer_id').val('');

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

