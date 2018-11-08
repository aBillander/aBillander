@extends('layouts.master')

@section('title') {{ l('Ecotaxes') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('ecotaxes/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Ecotaxes') }}
    </h2>        
</div>

<div id="div_ecotaxes">
   <div class="table-responsive">

@if ($ecotaxes->count())
<table id="ecotaxes" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="hidden">{{l('Country')}}</th>
            <th class="hidden">{{l('State')}}</th>
			<th>{{l('Ecotax name')}}</th>
            <th class="hidden">{{l('Ecotax Type')}}</th>
            <th>{{l('Ecotax Percent')}}</th>
            <th>{{l('Ecotax Amount')}}</th>
            <th class="hidden">{{l('Position')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($ecotaxes as $ecotax)
		<tr>
			<td>{{ $ecotax->id }}</td>
            <td class="hidden">{{ $ecotax->country->name }}</td>
            <td class="hidden">{{ $ecotax->state->name }}</td>
			<td>{{ $ecotax->name }}</td>
            <td class="hidden"><span class="label label-primary">{ { $ecotax_rule_typeList[$ecotax->rule_type] } }</span></td>
            <td>{{ $ecotax->as_percent('percent') }}%</td>
            <td>{{ $ecotax->as_money_amount('amount') }}</td>
            <td class="hidden">{{ $ecotax->position }}</td>

			<td class="text-right">
                @if (  is_null($ecotax->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('ecotaxes/' . $ecotax->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('ecotaxes/' . $ecotax->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Ecotaxes') }} :: ({{$ecotax->id}}) {{{ $ecotax->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('ecotaxes/' . $ecotax->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('ecotaxes/' . $ecotax->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
