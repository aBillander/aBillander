@extends('abcc.layouts.master')

@section('title') {{ l('My Vouchers') }} @parent @endsection


@section('content')

<div class="page-header">
    <!-- div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customervouchers/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div -->
    <h2>
        {{ l('My Vouchers') }}
    </h2>        
</div>

<div id="div_payments">

@if ($payments->count())
   <div class="table-responsive">

<table id="payments" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Invoice')}}</th>
			<!-- th>{{l('Customer')}}</th -->
			<th>{{l('Subject')}}</th>
			<th>{{l('Due Date')}}</th>
			<th>{{l('Payment Date')}}</th>
			<th>{{l('Amount')}}</th>
            <th class="text-center">{{l('Status', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($payments as $payment)
		<tr>
			<td>{{ $payment->id }}</td>
			<td>{{ $payment->customerInvoice->document_reference ?? '' }}</td>
			<!-- td>{{ $payment->customerInvoice->customer->name_fiscal ?? '' }}</td -->
			<td>{{ $payment->name }}</td>
			<td @if( !$payment->payment_date AND ( $payment->due_date < \Carbon\Carbon::now() ) ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<td>{{ abi_date_short($payment->abicc_payment_date) }}</td>
			<td>{{ abi_money_amount($payment->amount, $payment->currency) }}</td>
            <td class="text-center">
            	@if     ( $payment->abicc_status == 'pending' )
            		<span class="label label-info">
            	@elseif ( $payment->abicc_status == 'bounced' )
            		<span class="label label-danger">
            	@elseif ( $payment->abicc_status == 'paid' )  {{-- Consider voucher is in a Remitance that is financed --}}
            		<span class="label label-success">
            	@else
            		<span>
            	@endif
            	{{l( $payment->abicc_status, [], 'appmultilang' )}}</span>

            	@if ( $payment->auto_direct_debit && $payment->abicc_status == 'pending')

		            	<button class="btn btn-xs btn-navy" type="button" title="{{ l('This Voucher will be included in automatic payment remittances') }}">
				           <i class="fa fa-bank"></i>
				        </button>

            	@endif

            </td>

			<td class="text-right">
                <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $payments->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $payments->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif
</div>

@endsection

{{--
		@include('layouts/modal_delete')
--}}
