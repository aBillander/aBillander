@extends('abcc.layouts.master')

@section('title') {{ l('My Invoices') }} @parent @stop


@section('content')

<div class="page-header">
    <h2>
        {{ l('My Invoices') }}
    </h2>        
</div>

<div id="div_customer_invoices">
   <div class="table-responsive">

@if ($customer_invoices->count())
<table id="customer_invoices" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('Invoice #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <!-- th class="text-left">{{ l('Customer') }}</th -->
            <th class="text-left">{{ l('Payment Method') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th class="text-right">{{ l('Open Balance') }}</th>
            <th class="text-right">{{ l('Next Due Date') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customer_invoices as $invoice)
        <tr>
            <td>{{ $invoice->id }} / 
                @if ($invoice->document_id>0)
                {{ $invoice->document_reference }}
                @else
                <span class="label label-default" title="{{ l('Draft') }}">{{ l('Draft') }}</span>
                @endif</td>
            <td>{{ abi_date_short($invoice->document_date) }}</td>
            <!-- td><a class="" href="{ { URL::to('customers/' .$invoice->customer->id . '/edit') } }" title="{ { l('Show Customer') } }">
            	{ { $invoice->customer->name_fiscal } }
            	</a>
            </td -->
            <td>{{ $invoice->paymentmethod->name }}
            	<!-- a class="btn btn-xs btn-success" href="{{ URL::to('customerinvoices/' . $invoice->id) }}" title="{{ l('Show Payments') }}"><i class="fa fa-eye"></i></a -->
        	</td>
            <td class="text-right">{{ $invoice->as_money_amount('total_tax_incl') }}</td>
            <td class="text-right">{{ $invoice->as_money_amount('open_balance') }}</td>
            <td  @if( $invoice->next_due_date AND ( $invoice->next_due_date < \Carbon\Carbon::now() ) ) class="danger" @endif>
                @if ($invoice->open_balance < pow( 10, -$invoice->currency->decimalPlaces ) AND 0 ) 
                @else
                    @if ($invoice->next_due_date) {{ \App\FP::date_short($invoice->next_due_date) }} @endif
                @endif</td>
            <td class="text-right">

                <a class='btn btn-sm btn-lightblue show-shippingslips' href="#" data-target='#myModalShowShippingSlips' data-id="{{ $invoice->secure_key }}" data-toggle="modal" onClick="return false;" title="{{l('Show Shipping Slips')}}"><i class="fa fa-truck"></i></a>

                <a class='btn btn-sm btn-success show-payments' href="#" data-target='#myModalShowPayments' data-id="{{ $invoice->secure_key }}" data-toggle="modal" onClick="return false;" title="{{l('Show Payments')}}"><i class="fa fa-calendar"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ route('abcc.invoice.pdf',  ['invoiceKey' => $invoice->secure_key]) }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a> 

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

@endsection

@include('abcc.invoices._modal_shippingslips')

@include('abcc.invoices._modal_payments')
