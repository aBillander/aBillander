@extends('layouts.master')

@section('title') {{ l('Tax Rules') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('taxes/'.$tax->id.'/taxrules/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ URL::to('taxes') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Taxes') }}</a> 
    </div>
    <h2>
        <a href="{{ URL::to('taxes') }}">{{ l('Taxes') }}</a> <span style="color: #cccccc;">/</span> {{ $tax->name }}
    </h2>        
</div>

<div id="div_taxes">
   <div class="table-responsive">

@if ($taxrules->count())
<table id="taxes" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Country')}}</th>
            <th>{{l('State')}}</th>
            <th>{{l('Tax Rule Name')}}</th>
            <th>{{l('Tax Rule Type')}}</th>
            <th>{{l('Tax Rule Percent')}}</th>
            <th>{{l('Tax Rule Amount')}}</th>
            <th>{{l('Position')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($taxrules as $taxrule)
		<tr>
			<td>{{ $taxrule->id }}</td>
            <td>{{ $taxrule->country->name }}</td>
            <td>{{ $taxrule->state->name }}</td>
            <td>{{ $taxrule->name }}</td>
            <td><span class="label label-primary">{{ $tax_rule_typeList[$taxrule->rule_type] }}</span></td>
			<td>{{ $taxrule->as_percent('percent') }}%</td>
            <td>{{ $taxrule->as_money_amount('amount') }}</td>
            <td>{{ $taxrule->position }}</td>

			<td class="text-right">
                @if (  is_null($taxrule->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('taxes/' . $tax->id.'/taxrules/' . $taxrule->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('taxes/' . $tax->id.'/taxrules/' . $taxrule->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Tax Rules') }} :: ({{$taxrule->id}}) {{{ $taxrule->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('taxrules/' . $taxrule->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('taxrules/' . $taxrule->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
