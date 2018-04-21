@extends('layouts.master')

@section('title') {{ l('Customer Orders') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customerorders/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Customer Orders') }}
    </h2>        
</div>

<div id="div_customer_orders">
   <div class="table-responsive">

@if ($customer_orders->count())
<table id="customer_orders" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('Order #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Payment Method') }}</th>
            <th class="text-left">{{ l('Status', 'layouts') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customer_orders as $order)
        <tr>
            <td>{{ $order->id }} / 
                @if ($order->document_id>0)
                {{ $order->document_reference }}
                @else
                <span class="label label-default" title="{{ l('Draft') }}">{{ l('Draft') }}</span>
                @endif</td>
            <td>{{ abi_date_short($order->document_date) }}</td>
            <td>{{ abi_date_short($order->delivery_date) }}</td>
            <td><a class="" href="{{ URL::to('customers/' .$order->customer->id . '/edit') }}" title="{{ l('Show Customer') }}">
            	{{ $order->customer->name_fiscal }}
            	</a>
            </td>
            <td>{{ $order->paymentmethod->name ?? '' }}
            </td>
            <td>{{ \App\CustomerOrder::getStatusName($order->status) }}
            </td>
            <td class="text-right">{{ $order->as_money_amount('total_tax_incl') }}</td>
            <td class="text-right">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customerorders/' . $order->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->

                <a class="btn btn-sm btn-warning" href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if( $order->editable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customerorders/' . $order->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customer Orders') }} :: ({{$order->id}}) {{ $order->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
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
