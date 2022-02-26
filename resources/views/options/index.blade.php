@extends('layouts.master')

@section('title') {{ l('Options') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('optiongroups/'.$optiongroup->id.'/options/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a> 
        <a href="{{ URL::to('optiongroups') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Option Groups') }}</a> 
    </div> 
    <h2>
        <a href="{{ URL::to('optiongroups') }}">{{ l('Option Groups') }}</a> <span style="color: #cccccc;">/</span> {{ $optiongroup->name }}
    </h2>        
</div>

<div id="div_options">
   <div class="table-responsive">

@if ($options->count())
<table id="options" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Option Name')}}</th>
			<th>{{l('Position')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($options as $option)
		<tr>
			<td>{{ $option->id }}</td>
			<td>{{ $option->name }}</td>
			<td>{{ $option->position }}</td>

			<td class="text-right">
                @if (  is_null($option->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('optiongroups/'.$optiongroup->id.'/options/' . $option->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('optiongroups/'.$optiongroup->id.'/options/' . $option->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Options') }} :: ({{$option->id}}) {{{ $option->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('options/' . $option->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('options/' . $option->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
