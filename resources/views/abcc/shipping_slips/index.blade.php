@extends('abcc.layouts.master')

@section('title') {{ l('My Shipping Slips') }} @parent @endsection


@section('content')

<div class="page-header">
    <h2>
        {{ l('My Shipping Slips') }}
    </h2>        
</div>

<div id="div_documents">

@if ($documents->count())
   <div class="table-responsive">

<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('Shipping Slip #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Deliver to') }}</th>
            <th class="text-right">{{ l('Items') }}</th>
            <th class="text-right">{{ l('Total') }}</th>
            <th class="text-center">{{ l('Notes') }}</th>
            <th>{{ l('Invoiced at') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($documents as $document)
        <tr>
            <td>{{ $document->id }} / 

                <a href="{{ route('abcc.shippingslip.pdf', [$document->id]) }}" title="{{l('Show', [], 'layouts')}}" target="_blank">
                @if ($document->document_id>0)
                {{ $document->document_reference }}
                @else
                <span class="label label-default" title="{{ l('Draft') }}">{{ l('Draft') }}</span>
                @endif
                <span class="btn btn-sm btn-grey" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-file-pdf-o"></i></span>
                </a>
                </td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>{{ abi_date_short($document->delivery_date_real ?: $document->delivery_date) }}</td>
            <td>
                @if ( $document->hasShippingAddress() )



                {{ $document->shippingaddress->alias }} 
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $document->shippingaddress->firstname }} {{ $document->shippingaddress->lastname }}<br />{{ $document->shippingaddress->address1 }}<br />{{ $document->shippingaddress->city }} - {{ $document->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $document->shippingaddress->phone }}</a>" data-original-title="" title="">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      

                @endif
            </td>
            <td class="text-right">{{ $document->lines_count }}</td>
            <td class="text-right">{{ $document->as_money_amount('total_tax_incl') }}</td>
            <td class="text-center">@if ($document->notes_from_customer)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($document->notes_from_customer) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td>{{ abi_date_short($document->invoiced_at) }}</td>
            <td>
@if ( $document->invoice )
            <a href="{{ route('abcc.invoice.pdf',  ['invoiceKey' => $document->invoice->secure_key]) }}" title="{{l('Show', [], 'layouts')}}" target="_blank">{{ $document->invoice->document_reference }}
                <span class="btn btn-sm btn-grey" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-file-pdf-o"></i></span>
            </a>
@endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $documents->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $documents->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif
</div>

@endsection
