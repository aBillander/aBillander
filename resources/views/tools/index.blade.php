@extends('layouts.master')

@section('title') {{ l('Tools') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('tools/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Tools') }}
    </h2>        
</div>

<div id="div_tools">
   <div class="table-responsive">

@if ($tools->count())
<table id="tools" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Reference') }}</th>
            <th class="text-left">{{l('Name')}}</th>
            <th class="text-left">{{l('Tool type')}}</th>
            <th class="text-left">{{l('Location')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tools as $tool)
        <tr>
            <td>{{ $tool->id }}</td>

            <td>{{ $tool->reference }}</td>

            <td>{{ $tool->name }}
            </td>

            <td>{{ $tool->tool_type }}</td>

            <td>{{ $tool->location }}
            </td>

            <td class="text-right">
                @if (  is_null($tool->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('tools/' . $tool->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('tools/' . $tool->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Tools') }} ::  ({{$tool->id}}) {{ $tool->alias }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('tools/' . $tool->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('tools/' . $tool->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
