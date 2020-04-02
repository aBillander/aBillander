@extends('layouts.master')

@section('title') {{ l('Shipping Method Rules') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ route('shippingmethods.shippingmethodrules.create', [$shippingmethod->id]) }}" class="btn btn-sm btn-success" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ route('shippingmethods.index') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Shipping Methods') }}</a> 
    </div>
    <h2>
        <a href="{{ route('shippingmethods.index') }}">{{ l('Shipping Method Rules') }}</a> <span style="color: #cccccc;">/</span> {{ $shippingmethod->name }}

        <a href="javascript:void(0);" class="btn btn-info btn-xs">{{ $shippingmethod->billing_type_name }}</a>
        <span class="badge" style="background-color: #3a87ad;" title="{{ \App\Context::getContext()->currency->name }}">{{ \App\Context::getContext()->currency->iso_code }}</span>
    </h2>        
</div>

<div id="div_taxes">
   <div class="table-responsive">

@if ($shippingmethod->rules->count())
<table id="taxes" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Country')}}</th>
            <th>{{l('State')}}</th>
            <th>{{l('Postal Code')}}</th>
            <th>{{l('From amount')}} </th>
            <th>{{l('Price')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($shippingmethod->rules as $line)
		<tr>
			<td>{{ $line->id }}</td>
            <td>{{ optional($line->country)->name }}</td>
            <td>{{ optional($line->state)->name }}</td>
            <td>{{ $line->postcode }}</td>
			<td>{{ $line->as_quantity('from_amount') }}</td>
            <td>{{ $line->as_money_amount('price') }}</td>

			<td class="text-right">
                @if (  is_null($line->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ route('shippingmethods.shippingmethodrules.edit', [$shippingmethod->id, $line->id]) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ route('shippingmethods.shippingmethodrules.destroy', [$shippingmethod->id, $line->id]) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Shipping Method Rules') }} :: ({{$line->id}}) {{ optional($line->country)->name }} {{ optional($line->state)->name }} {{ $line->postcode }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
{{--
                <a class="btn btn-warning" href="{{ URL::to('taxrules/' . $line->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('taxrules/' . $line->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
--}}
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
