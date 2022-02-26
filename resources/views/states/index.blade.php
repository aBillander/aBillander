@extends('layouts.master')

@section('title') {{ l('States') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('countries/'.$country->id.'/states/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a> 
        <a href="{{ URL::to('countries') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Countries') }}</a> 
    </div> 
    <h2>
        <a href="{{ URL::to('countries') }}">{{ l('Countries') }}</a> <span style="color: #cccccc;">/</span> {{ $country->name }}
    </h2>        
</div>

<div id="div_states">
   <div class="table-responsive">

@if ($states->count())
<table id="states" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('State Name')}}</th>
            <th>{{l('State ISO code')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($states as $state)
		<tr>
			<td>{{ $state->id }}</td>
			<td>{{ $state->name }}</td>
            <td>{{ $state->iso_code }}</td>
            
            <td class="text-center">@if ($state->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<td class="text-right">
                @if (  is_null($state->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('countries/'.$country->id.'/states/' . $state->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('countries/'.$country->id.'/states/' . $state->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('States') }} :: ({{$state->id}}) {{{ $state->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('states/' . $state->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('states/' . $state->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
