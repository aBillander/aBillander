@extends('layouts.master')

@section('title') {{ l('Warehouses') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('warehouses/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Warehouses') }}
    </h2>        
</div>

<div id="div_warehouses">
   <div class="table-responsive">

@if ($warehouses->count())
<table id="warehouses" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Alias')}}</th>
            <th class="text-left">{{l('Address')}}</th>
            <th class="text-left">{{l('Contact')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($warehouses as $warehouse)
        <tr>
            <td>{{ $warehouse->id }}</td>
            <td>{{ $warehouse->address->alias }}</td>

            <td>{{ $warehouse->address->name_commercial }}<br />
                {{ $warehouse->address->address1 }} {{ $warehouse->address->address2 }}<br />
                {{ $warehouse->address->postcode }} {{ $warehouse->address->city }}, {{ $warehouse->address->state->name }}<br />
                {{ $warehouse->address->country->name }}
            </td>

            <td>{{ $warehouse->address->firstname }} {{ $warehouse->address->lastname }}<br />
                {{ $warehouse->address->phone }} &nbsp; {{ $warehouse->address->phone_mobile }}<br />
                {{ $warehouse->address->email }}
            </td>
            <td class="text-center">
                @if ($warehouse->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $warehouse->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
            
            <td class="text-center">@if ($warehouse->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-right">
                @if (  is_null($warehouse->deleted_at))

                <a class="btn btn-sm btn-blue" href="{{ route('warehouse.inventory', $warehouse->id) }}" title="{{l('Products in Warehouse')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('warehouses/' . $warehouse->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('warehouses/' . $warehouse->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Warehouses') }} ::  ({{$warehouse->id}}) {{ $warehouse->alias }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('warehouses/' . $warehouse->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('warehouses/' . $warehouse->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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

@include('layouts/modal_delete')
