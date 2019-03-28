@extends('layouts.master')

@section('title') {{ l('Customer Groups') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customergroups/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Customer Groups') }}
    </h2>        
</div>

<div id="div_customergroups">
   <div class="table-responsive">

@if ($customergroups->count())
<table id="customergroups" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Customer Group Name')}}</th>
            <th class="text-left">{{l('Price List')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customergroups as $group)
        <tr>
            <td>{{ $group->id }}</td>
            <td>{{ $group->name }}</td>
            <td>{{ optional($group->pricelist)->name }}</td>
            
            <td class="text-center">@if ($group->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-right">
                @if (  is_null($group->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('customergroups/' . $group->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customergroups/' . $group->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customer Groups') }} ::  ({{$group->id}}) {{ $group->name }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('customergroups/' . $group->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('customergroups/' . $group->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
