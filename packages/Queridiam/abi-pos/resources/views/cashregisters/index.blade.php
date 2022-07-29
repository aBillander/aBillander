@extends('layouts.master')

@section('title') {{ l('Cash Registers') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('pos/cashregisters/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Cash Registers') }}
    </h2>        
</div>

<div id="div_cashregisters">
   <div class="table-responsive">

@if ($cashregisters->count())
<table id="cashregisters" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Reference') }}</th>
            <th class="text-left">{{l('Name')}}</th>
            <th class="text-left">{{l('Cash Register type')}}</th>
            <th class="text-left">{{l('Location')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cashregisters as $cashregister)
        <tr>
            <td>{{ $cashregister->id }}</td>

            <td>{{ $cashregister->reference }}</td>

            <td>{{ $cashregister->name }}
            </td>

            <td>{{ $cashregister->cashregister_type }}</td>

            <td>{{ $cashregister->location }}
            </td>

            <td class="text-right">
                @if (  is_null($cashregister->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('pos/cashregisters/' . $cashregister->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('pos/cashregisters/' . $cashregister->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Cash Registers') }} ::  ({{$cashregister->id}}) {{ $cashregister->alias }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('pos/cashregisters/' . $cashregister->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('pos/cashregisters/' . $cashregister->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
