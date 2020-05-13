@extends('layouts.master')

@section('title') {{ l('WooCommerce Customer Orders - Show') }} @parent @stop


@section('content')

{{-- abi_r($order, true) --}}

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <!-- a href="{{ URL::to('orders/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a -->
        <!-- div class="btn-group">
            <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-plus"></i> {{l('Actions', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{ URL::route('worders.import' , [$order["id"]] ) }}"><i class="fa fa-download" ></i> &nbsp; {{l('Import')}}</a></li>
              <li><a href="{{ URL::route('worders.invoice', [$order["id"]] ) }}"><i class="fa fa-file-text"></i> &nbsp; {{l('Invoice')}}</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
            </ul>
        </div -->
        <a href="{{ URL::to('wooc/worders') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to WooCommerce Orders') }}</a> 
    </div>
    <h2>
        <a href="{{ URL::to('wooc/worders') }}">{{ l('WooCommerce Orders') }}</a> <span style="color: #cccccc;">/</span> #{{ $order['id'] }} <button type="button" class="btn btn-sm btn-blue">{{ $order['currency'] }}</button>
    </h2>        
</div>

@if ($order )

<div class="row">

{{-- Order Header --}}

    <div class="col-sm-4">
        
        <div class="form-group col-sm-6">
            {{l('WooC Order #')}}
            <div class="input-group">
            <div class="form-control">{{ $order['id'] }}</div>
@if ( $order["imported_at"] )                
                <span class="input-group-btn">
                    <a href="{{ URL::route('customerorders.edit', [$order["abi_order_id"]] ) }}" class="btn btn-success" title="{{ l('Go to Customer Order') }}" target="_blank">
                        <span class="fa fa-eye"></span>
                    </a>
                </span>
@else               
                <span class="input-group-addon" title="{{ l('This Customer Order has not been imported') }}">
                    <span class="fa fa-eye-slash"></span>
                </span>
@endif
            </div>
        </div>
        
        <div class="form-group col-sm-6">
            {{l('Status')}}
            <div class="form-control">{{  \aBillander\WooConnect\WooConnector::getOrderStatusName($order["status"]) }}</div>
        </div>
        
        <!-- div class="form-group col-sm-6">
            {{l('Currency')}}
            <div class="form-control">{ { $order['currency'] }}</div>
        </div>
        
        <div class="form-group col-sm-6">
            {{l('Currency Conversion Rate')}}
            <div class="form-control"> </div>
        </div -->
        
        <div class="form-group col-sm-6">
            {{l('Created at')}}
            <div class="form-control">{{ \Carbon\Carbon::parse($order['date_created']) }}</div>
        </div>
        
        <div class="form-group col-sm-6">
            {{l('Paid at')}}
            <div class="form-control">{{ \Carbon\Carbon::parse($order['date_paid']) }}</div>
        </div>
        
        <!-- div class="form-group col-sm-6">
            {{l('Downloaded at')}}
            <div class="form-control">{ { $order["date_downloaded"] }}</div>
        </div>
        
        <div class="form-group col-sm-6">
            {{l('Invoiced at')}}
            <div class="form-control"> </div>
        </div -->

        {{-- Addresses --}}

        <div class="row">
        <div class="xform-group col-sm-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">{{ l('Shipping Address') }}</h3>
              </div>
              <div class="panel-body">

@if ( $order['has_shipping'] )

            <div class="alert alert-dismissible alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> <span class="fa fa-warning"></span> </strong> {{ l('Shipping Address is different from Billing Address!') }}
            </div>

@php

$extra = [];

$extra['email'] = $order['shipping']['email'] ?? $order['billing']['email'];
$extra['phone'] = $order['shipping']['phone'] ?? $order['billing']['phone'];

@endphp

                @include('woo_connect::woo_orders._block_address', ['address' => $order['shipping'] + $extra])

@else

            <div class="panel panel-default">
                <div class="panel-body">
                    <strong> <span class="text-warning fa fa-thumbs-o-up"></span> </strong> {{ l('Shipping Address is the same as Billing Address.') }}
                </div>
            </div>

@endif

              </div>
            </div>
        </div>
        </div>

        <div class="row">
        <div class="xform-group col-sm-12">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">{{ l('Billing Address') }}</h3>
              </div>
              <div class="panel-body">

                @include('woo_connect::woo_orders._block_address', ['address' => $order['billing']])
                
              </div>
            </div>
        </div>
        </div>

    </div>

{{-- Order Details --}}

    <div class="col-sm-8">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ l('Reference') }}</th>
                        <th>{{ l('Description') }}</th>
                        <th class="text-right">{{ l('Quantity') }}</th>
                        <th class="text-right">{{ l('Total') }}</th>
                    </tr>
                </thead>

                @foreach( $order['line_items'] as $item )
                <tr>
                    <td>{{ $item['sku'] }}</td>
                    <td>
                        {{ $item['name'] }}
                        @if( isset($item['meta']) )
	                        @foreach( $item['meta'] as $meta )
	                        	<br/><b>{{ $meta['label'] }}:</b> {{ $meta['value'] }}
	                        @endforeach
                        @endif
                    </td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ $item['subtotal'] + $item['subtotal_tax'] }}</td>
                </tr>
                @endforeach

                @foreach( $order['coupon_lines'] as $item )
                <tr class="warning">
                    <td></td>
                    <td>{{ l('Coupon') }}: {{$item['code']}}</td>
                    <td class="text-right"></td>
                    <td class="text-right">-{{ $item['discount'] + $item['discount_tax'] }}</td>
                </tr>
                @endforeach
                
                @foreach( $order['shipping_lines'] as $item )
                <tr class="info">
                    <td></td>
                    <td><span class="fa fa-truck"></span> &nbsp; {{$item['method_title']}}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{ $item['total'] + $item['total_tax'] }}</td>
                </tr>
                @endforeach
                
                @foreach( $order['fee_lines'] as $item )
                <tr class="warning">
                    <td></td>
                    <td><span class="fa fa-archive-o"></span> &nbsp; {{$item['method_title']}}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{ $item['total'] + $item['total_tax'] }}</td>
                </tr>
                @endforeach

                <tr>
                    <td colspan="4" class="text-right">
                        <b>{{$order['total']}} {{ $order['currency'] }}</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right {{ $order['date_paid'] ? 'success' : 'danger' }}">
                        <b>{{ l('Payment method') }}:</b> {{ '('.$order['payment_method'].') '.$order['payment_method_title'] }}

                        @if ( $order['date_paid'] )
                        &nbsp; <span class="fa fa-check-circle" title="{{ l('Paid') }}"></span><br />
                        {{ l('Transaction ID') }}: &nbsp;{{ $order['transaction_id'] ? $order['transaction_id'] : ' - - - - - ' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right warning">
                        <b>{{ l('Shipping method') }}:</b> {{ $order['shipping']['shipping_method'] }}

                        <br />
                        <b>{{ l('Carrier') }}:</b> &nbsp;{{ $order['shipping']['carrier'] ? $order['shipping']['carrier'] : ' - - - - - ' }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="form-group">
            <label class="control-label">{{ l('Customer Notes') }}</label>
            <textarea class="form-control" rows="2" onfocus="this.blur();" xreadonly="">{{ $order['customer_note'] }}</textarea>
        </div>
    </div>
</div>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif


{{-- !! abi_r($order) !! --}}

@endsection