@extends('layouts.master')

@section('title') {{ l('Customer Invoices') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customerinvoices/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Customer Invoices') }}
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
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Payment Method') }}</th>
            <th class="text-left" colspan="3"> </th>
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
            <td><a class="" href="{{ URL::to('customers/' .$invoice->customer->id . '/edit') }}" title="{{ l('Show Customer') }}">
            	{{ $invoice->customer->name_fiscal }}
            	</a>
            </td>
            <td>{{ $invoice->paymentmethod->name }}
            	<a class="btn btn-xs btn-success" href="{{ URL::to('customerinvoices/' . $invoice->id) }}" title="{{ l('Show Payments') }}"><i class="fa fa-eye"></i></a>
        	</td>
            <td>@if ( $invoice->editable) <span class="label label-default" title="{{ l('Draft') }}">{{ l('D') }}</span> @endif</td>
            <td>@if (!$invoice->einvoice_sent) <span class="label label-primary" title="{{ l('Pending: Send by eMail') }}">{{ l('eM') }}</span> @endif
            	@if (!$invoice->printed) <span class="label label-warning" title="{{ l('Pending: Print and Send') }}">{{ l('Pr') }}</span> @endif</td>
            <td>@if ( $invoice->status == 'paid') <span class="label label-success" title="{{ l('Paid') }}">{{ l('OK') }}</span> @endif</td>
            <td class="text-right">{{ $invoice->as_money_amount('total_tax_incl') }}</td>
            <td class="text-right">{{ $invoice->as_money_amount('open_balance') }}</td>
            <td  @if( $invoice->next_due_date AND ( $invoice->next_due_date < \Carbon\Carbon::now() ) ) class="danger" @endif>
                @if ($invoice->open_balance < pow( 10, -$invoice->currency->decimalPlaces ) AND 0 ) 
                @else
                    @if ($invoice->next_due_date) {{ abi_date_short($invoice->next_due_date) }} @endif
                @endif</td>
            <td class="text-right">
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customerinvoices/' . $invoice->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{{ URL::to('customerinvoices/' . $invoice->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('customerinvoices/' . $invoice->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if( $invoice->editable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customerinvoices/' . $invoice->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customer Invoices') }} :: ({{$invoice->id}}) {{ $invoice->document_reference }} " 
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
