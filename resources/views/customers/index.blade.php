@extends('layouts.master')

@section('title') {{ l('Customers') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customers/create') }}" class="btn btn-sm btn-success" 
                 title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Customers') }}
    </h2>        
</div>

<div id="div_customers">
   <div class="table-responsive">

@if ($customers->count())
<table id="customers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('ID', [], 'layouts') }}</th>
            <th class="text-left">{{ l('Name') }}</th>
            <th class="text-left">{{ l('Identification') }}</th>
            <th class="text-left">{{ l('Email') }}</th>
            <th class="text-left">{{ l('Phone') }}</th>
            <th class="text-left">{{ l('Outstanding Amount') }}</th>
            <th class="text-center">{{ l('Blocked', [], 'layouts') }}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
        <tr>
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->name_fiscal }}<br />{{ $customer->name_commercial }}</td>
            <td>{{ $customer->identification }}</td>
            <td>{{ $customer->address->email }}</td>
            <td>{{ $customer->address->phone }}</td>
            <td @if ( ($customer->outstanding_amount - $customer->outstanding_amount_allowed)>0 ) class="alert alert-danger" @endif>{{ $customer->as_money('outstanding_amount', $customer->currency) }}</td>
            <td class="text-center">@if ($customer->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>
            <td class="text-right">
                @if (  is_null($customer->deleted_at))
                
                <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                        xhref="{{ URL::to('customers/' . $customer->id) . '/mail' }}" 
                        href="{{ URL::to('mail') }}" 
                        data-to_name = "{{ $customer->address->firstname }} {{ $customer->address->lastname }}" 
                        data-to_email = "{{ $customer->address->email }}" 
                        data-from_name = "{{ \App\Context::getContext()->user->getFullName() }}" 
                        data-from_email = "{{ \App\Context::getContext()->user->email }}" 
                        onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>
                
                <div class="btn-group">
                    <a href="#" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Document', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('customer.createorder', $customer->id) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('customers/' . $customer->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customers/' . $customer->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customers') }} :: ({{$customer->id}}) {{{ $customer->name_fiscal }}} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('customers/' . $customer->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('customers/' . $customer->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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


@section('styles')    @parent

{{-- 
 - Fix drop down button menu scroll
 - https://stackoverflow.com/questions/26018756/bootstrap-button-drop-down-inside-responsive-table-not-visible-because-of-scroll
--}}

<style>
    .table-responsive {
      overflow-x: visible !important;
      overflow-y: visible !important;
    }
</style>

@endsection

@include('layouts/modal_mail')
@include('layouts/modal_delete')
