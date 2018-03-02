@extends('layouts.master')

@section('title') {{ l('Orders') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('wooc/worders') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to WooCommerce Orders') }}</a> 
    </div>
    <h2>
        {{ l('Imported Orders') }}
    </h2>        
</div>

<div id="div_orders">
   <div class="table-responsive">

@if ($orders->count())
<table id="orders" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Order key')}}</th>
			<th>{{l('Created at')}}</th>
			<th>{{l('Paid at')}}</th>
			<th>{{l('Imported at')}}</th>
			<th>{{l('Invoiced at')}}</th>
			<th>{{l('Total')}}</th>
			<!-- th>{{l('Currency')}}</th -->
			<th>{{l('Customer')}}</th>
			<th>{{l('Customer note')}}</th>
			<th>{{l('Payment Method')}}</th>
			<th>{{l('Shipping Method')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($orders as $order)
		<tr>
			<td>{{ $order->id }}</td>
			<td>{{ str_replace('wc_order_', '', $order->order_key) }}</td>
			<td>{{ abi_date_short($order->date_created) }}</td>
			<td>{{ abi_date_short($order->date_paid) }}</td>
			<td>{{ abi_date_short($order->date_abi_exported) }}</td>
			<td>{{ abi_date_short($order->date_abi_invoiced) }}</td>
			<td>{{ $order->total }} {{ $order->currency }}</td>
			<td>
					<a href="{{ URL::to('customers/' . $order->customer->id) }}" class="btn btn-sm btn-grey" title="{{ l('Go to Customer') }}" target="_blank">
                        <span class="fa fa-eye"></span> {{ $order->customer->name_commercial }}
                    </a>
			</td>
			<td>{{ $order->customer_note }}</td>
			<td>{{ $order->payment_method_title }}</td>
			<td>{{ $order->shipping_method_title }}</td>

			<td class="text-right">

                <!-- a class="btn btn-sm btn-blue" href="{ { URL::to('orders/' . $order->id . '/exchange') }}" title="{ {l('Show Conversion Rate history')}}"><i class="fa fa-bank"></i></a -->    

                <!-- a class="btn btn-sm btn-success" href="{ { URL::to('orders/' . $order->id . '/show') } }" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a -->

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
