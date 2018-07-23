@extends('layouts.master')

@section('title') {{ l('Sales Representatives') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('salesreps/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Sales Representatives') }}
    </h2>        
</div>

<div id="div_salesreps">
   <div class="table-responsive">

@if ($salesreps->count())
<table id="salesreps" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <!-- th class="text-left">{{l('Alias')}}</th -->
            <th class="text-left">{{l('Identification')}}</th>
            <th class="text-left">{{l('Contact')}}</th>
            <th class="text-left">{{l('Address')}}</th>
            <th class="text-left">{{l('Commission (%)')}}</th>
            <th class="text-left">{{l('Max. Discount allowed (%)')}}</th>
            <th class="text-left">{{l('Withholdings (%)')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($salesreps as $salesrep)
        <tr>
            <td>{{ $salesrep->id }}</td>
            <!-- td>{{ $salesrep->alias }}</td -->
            <td>{{ $salesrep->identification }}</td>

            <td>{{ $salesrep->name }}<br />
                {{ $salesrep->phone }} &nbsp; {{ $salesrep->phone_mobile }}<br />
                {{ $salesrep->email }}
            </td>

            <td>{{-- $salesrep->address->name_commercial }}<br />
                {{ $salesrep->address->address1 }} {{ $salesrep->address->address2 }}<br />
                {{ $salesrep->address->postcode }} {{ $salesrep->address->city }}, {{ $salesrep->address->state }}<br />
                {{ $salesrep->address->country --}}
            </td>
            <td>{{ ( $salesrep->commission_percent ) }}</td>
            <td>{{ ( $salesrep->max_discount_allowed ) }}</td>
            <td>{{ ( $salesrep->pitw ) }}</td>
            <td class="text-center">
                @if ($salesrep->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $salesrep->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
            
            <td class="text-center">@if ($salesrep->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-right">
                @if (  is_null($salesrep->deleted_at))
                <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                        xhref="{{ URL::to('salesreps/' . $salesrep->id) . '/mail' }}" 
                        href="{{ URL::to('mail') }}" 
                        data-to_name = "{{ $salesrep->name }}" 
                        data-to_email = "{{ $salesrep->email }}" 
                        data-from_name = "{{ \App\Context::getContext()->user->getFullName() }}" 
                        data-from_email = "{{ \App\Context::getContext()->user->email }}" 
                        onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('salesreps/' . $salesrep->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('salesreps/' . $salesrep->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Sales Representatives') }} :: ({{$salesrep->id}}) {{ $salesrep->name }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('salesreps/' . $salesrep->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('salesreps/' . $salesrep->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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

@endsection

@include('layouts/modal_mail')
@include('layouts/modal_delete')
