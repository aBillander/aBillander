@extends('layouts.master')

@section('title') {{ l('Ecotax Rules') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('ecotaxes/'.$ecotax->id.'/ecotaxrules/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ URL::to('ecotaxes') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Ecotaxes') }}</a> 
    </div>
    <h2>
        <a href="{{ URL::to('ecotaxes') }}">{{ l('Ecotaxes') }}</a> <span style="color: #cccccc;">/</span> {{ $ecotax->name }}
    </h2>        
</div>

<div id="div_ecotaxes">
   <div class="table-responsive">

@if ($ecotaxrules->count())
<table id="ecotaxes" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Country')}}</th>
            <th>{{l('State')}}</th>
            <th>{{l('Ecotax Rule Name')}}</th>
            <th>{{l('Ecotax Rule Type')}}</th>
            <th>{{l('Ecotax Rule Percent')}}</th>
            <th>{{l('Ecotax Rule Amount')}}</th>
            <th>{{l('Position')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($ecotaxrules as $ecotaxrule)
		<tr>
			<td>{{ $ecotaxrule->id }}</td>
            <td>{{ $ecotaxrule->country->name }}</td>
            <td>{{ $ecotaxrule->state->name }}</td>
            <td>{{ $ecotaxrule->name }}</td>
            <td><span class="label label-primary">{{ $ecotax_rule_typeList[$ecotaxrule->rule_type] }}</span></td>
			<td>{{ $ecotaxrule->as_percent('percent') }}%</td>
            <td>{{ $ecotaxrule->as_money_amount('amount') }}</td>
            <td>{{ $ecotaxrule->position }}</td>

			<td class="text-right">
                @if (  is_null($ecotaxrule->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('ecotaxes/' . $ecotax->id.'/ecotaxrules/' . $ecotaxrule->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('ecotaxes/' . $ecotax->id.'/ecotaxrules/' . $ecotaxrule->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Ecotax Rules') }} :: ({{$ecotaxrule->id}}) {{{ $ecotaxrule->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('ecotaxrules/' . $ecotaxrule->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('ecotaxrules/' . $ecotaxrule->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
