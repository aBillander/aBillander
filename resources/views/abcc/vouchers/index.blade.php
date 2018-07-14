@extends('abcc.layouts.master')

@section('title') {{ l('Customer Vouchers') }} @parent @stop


@section('content')

<div class="page-header">
    <!-- div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customervouchers/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div -->
    <h2>
        {{ l('Customer Vouchers') }}
    </h2>        
</div>

<div id="div_payments">
   <div class="table-responsive">

@if ($payments->count())
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
			<td>{{ $payment->customerInvoice->document_reference or '' }}</td>
			<!-- td>{{ $payment->customerInvoice->customer->name_fiscal or '' }}</td -->
			<td>{{ $payment->name }}</td>
			<td @if( !$payment->payment_date AND ( \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $payment->due_date) < \Carbon\Carbon::now() ) ) class="danger" @endif>
				{{ $payment->due_date }}</td>
			<td>{{ $payment->payment_date }}</td>
			<td>{{ $payment->amount }}</td>
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
            	{{l( $payment->status, [], 'appmultilang' )}}</span></td>

			<td class="text-right">
                <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@stop

@include('layouts/modal_delete')
