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
    <div class="col-sm-4">
        
        <div class="form-group col-sm-6">
            {{l('WooC Order #')}}
            <div class="form-control">{{ $order['id'] }}</div>
        </div>
        
        <div class="form-group col-sm-6">
            {{l('Status')}}
            <div class="form-control">{{  \aBillander\WooConnect\WooConnector::getOrderStatusName($order["status"]) }}</div>
        </div>
        
        <!-- div class="form-group col-sm-6">
            {{l('Currency')}}
            <div class="form-control">{{ $order['currency'] }}</div>
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
            <div class="form-control">{{ $order["date_downloaded"] }}</div>
        </div>
        
        <div class="form-group col-sm-6">
            {{l('Invoiced at')}}
            <div class="form-control"> </div>
        </div -->

        <div class="form-group">
            {{ l('Customer') }}:
            <div class="input-group">
                <div class="form-control">{{ $order['customer_id'] }} - {{ $order['billing']['first_name'].' '.$order['billing']['last_name'] }}</div>
@if ( $customer )                
                <span class="input-group-btn">
                    <a href="{{ URL::to('customers/' . $customer->id) }}" class="btn btn-success" title="{{ l('Go to Customer') }}" target="_blank">
                        <span class="fa fa-eye"></span>
                    </a>
                </span>
@else               
                <span class="input-group-addon" title="{{ l('This Customer has not been imported') }}">
                    <span class="fa fa-eye-slash"></span>
                </span>
@endif                
            </div>
        </div>
        
        <div class="form-group">
            {{ l('Company') }}:
            <div class="form-control">{{ $order['billing']['company'] }}</div>
        </div>
        
        <div class="form-group">
            {{ l('Address') }}:
            <div class="form-control">{!! $order["shipping"]["address_1"].(isset($order["shipping"]["address_2"])?'<br />'.$order["shipping"]["address_2"]:'') !!}</div>
        </div>
        
        <!-- div class="form-group">
            {{ l('VAT Number') }}
            <div class="form-control">{{ $order['billing']['vat_number'] }}</div>
        </div -->
        
        <!-- div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa fa-envelope"></span>
                </span>
                <div class="form-control">{{ $order['billing']['email'] }}</div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa fa-phone"></span>
                </span>
                <div class="form-control">{{ $order['billing']['phone'] }}</div>
            </div>
        </div -->
        
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa fa-envelope"></span>
                </span>
                <div class="form-control" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">{{ $order['billing']['email'] }}</div>
                <span class="input-group-addon" style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                    <span class="fa fa-phone"></span>
                </span>
                <div class="form-control">{{ $order['billing']['phone'] }}</div>
            </div>
        </div>
        
        <div class="form-group">
            {{ l('City') }}:
            <div class="form-control">{{ $order['shipping']['postcode'].' - '.$order['shipping']['city'] }}</div>
        </div>
        <div class="form-group">
            <span class="text-capitalize">{{ l('State') }}</span>
            <div class="input-group">
                <div class="form-control">{{ $order['shipping']['state_name'] }}</div>
                <span class="input-group-addon">
                    {{ $order['shipping']['country_name'] }}
                </span>
            </div>
        </div>
    </div>
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
            {{ l('Customer Notes') }}
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


            </div>

		</div>
	</div>

{{-- !! abi_r($order) !! --}}

@endsection