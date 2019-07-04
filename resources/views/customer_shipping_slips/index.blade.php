@extends('layouts.master')

@section('title') {{ l('Documents') }} @parent @stop


@section('content')


<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        
        <div class="btn-group xopen">
          <a href="{{ route($model_path.'.index') }}" class="btn btn-success btn-sm" title="{{l('Filter Records', [], 'layouts')}}"><i class="fa fa-filter"></i> &nbsp;{{l('All', [], 'layouts')}}</a>

          <a href="#" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></a>

          <ul class="dropdown-menu">
            <li><a href="{{ route($model_path.'.index', 'invoiced_not') }}"><i class="fa fa-exclamation-triangle text-danger"></i> &nbsp; {{l('Not Invoiced')}}</a>
            </li>

            <li><a href="{{ route($model_path.'.index', 'invoiced') }}"><i class="fa fa-money text-muted"></i> &nbsp; {{l('Invoiced')}}</a>
            </li>

            <li class="divider"></li>
          </ul>
        </div>

        <a href="{{ URL::to($model_path.'/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>


    </div>
    <h2>
        {{ l('Documents') }}
    </h2>        
</div>

<div id="div_documents">

   <div class="table-responsive">

@if ($documents->count())
<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('ID', 'layouts') }}</th>
            <th class="text-center"></th>
            <th class="text-left">{{ l('Date') }}</th>
            <th> </th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Deliver to') }}</th>
            <th class="text-left">{{ l('Created via') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th class="text-center">{{ l('Notes', 'layouts') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="document_lines">
        @foreach ($documents as $document)
        <tr>
            <td>{{ $document->id }} / 
                @if ($document->document_id>0)
                {{ $document->document_reference }}
                @else
                <a class="btn btn-xs btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                <span xclass="label label-default">{{ l('Draft') }}</span>
                </a>
                @endif</td>
            <td class="text-center">

@if ($document->invoiced_at && $document->customerinvoice())
                <a class="btn btn-xs btn-success" href="{{ URL::to('customerinvoices/' . $document->customerinvoice()->id . '/edit') }}" title="{{ l('Invoiced at:') }} {{abi_date_short( $document->invoiced_at )}}"><i class="fa fa-money"></i></a>
@else
    @if ( $document->status == 'closed' )
                <a class="btn btn-xs alert-danger" href="#" title="{{l('Document closed', 'layouts')}}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-lock"></i>&nbsp;</a>
    @else
        @if ($document->onhold>0)
                    <a class="btn btn-xs btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
        @else
                    <a class="btn btn-xs alert-info" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
        @endif
    @endif
@endif

@if ( $document->edocument_sent_at )
                <a class="btn btn-xs alert-success" href="#" title="{{l('Email sent:')}} {{ abi_date_short($document->document_date) }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-envelope-o"></i>&nbsp;</a>
@endif
                
            </td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>
    @if ( $document->shipment_status == 'delivered' )
                <a class="btn btn-xs btn-blue" href="#" title="{{ l('Delivered at:') }} {{abi_date_short( $document->delivery_date_real )}}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-truck"></i>&nbsp;</a>
    @else
        @if ($document->status == 'closed')
                <a class="btn btn-xs alert-danger" href="{{ URL::to($model_path.'/' . $document->id . '/deliver') }}" title="{{l('Set delivered')}}">&nbsp;<i class="fa fa-truck"></i>&nbsp;</a>
        @endif
    @endif
            </td>
            <td>{{ abi_date_short($document->delivery_date) }}</td>
            <td><a class="" href="{{ URL::to('customers/' . optional($document->customer)->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ optional($document->customer)->name_regular }}
            	</a>
            </td>
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
            <td>{{ $document->created_via }}
            </td>
            <td class="text-right">{{ $document->as_money_amount('total_tax_incl') }}</td>
            <td class="text-center">@if ($document->all_notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($document->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-right button-pad">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customeror ders/' . $document->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{ { URL::to('customer orders/' . $document->id) } }" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->
@if ( \App\Configuration::isTrue('DEVELOPER_MODE') && 0)

                <a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id . '/duplicate') }}" title="{{l('Copy', 'layouts')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-info" href="{{ URL::to($model_path.'/' . $document->id . '/invoice/pdf') }}" title="{{l('PDF Invoice', [], 'layouts')}}"><i class="fa fa-money"></i></a>

                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('customer orders/' . $document->id . '/shippingslip') }}" title="{{l('Document', [], 'layouts')}}"><i class="fa fa-file-pdf-otruck"></i></a -->

                <a class="btn btn-sm btn-lightblue xbtn-info" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-truck"></i></a>
@endif

@if ($document->document_id>0)
                <a class="btn btn-sm btn-lightblue"    href="{{ URL::to($model_path.'/' . $document->id . '/email') }}" title="{{l('Send by eMail', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
@endif

                <!-- a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a -->

@if ($document->onhold>0)

@else

                @if ( $document->status == 'closed' && !$document->invoiced_at)
                <a class="btn btn-sm btn-navy" href="{{ route('customershippingslip.invoice', [$document->id]) }}" title="{{l('Create Invoice')}}"><i class="fa fa-money"></i>
                </a>
                @endif
@endif

@if ( !$document->invoiced_at )
                <a class="btn btn-sm btn-warning" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
@endif

                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to($model_path.'/' . $document->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Documents') }} :: ({{$document->id}}) {{ $document->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $documents->appends( collect(Request::all())
                            ->map(function($item) {
                                    // Take empty keys, otherwise skipped!
                                    return is_null($item) ? 1 : $item;
                            })->toArray() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $documents->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_documents" ENDS -->

@endsection

@include('layouts/modal_delete')


{{-- *************************************** --}}



@if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') )

@if ($model_path=='customerorders')


        @include($view_path.'._chunck_manufacturing')


@endif

@endif
