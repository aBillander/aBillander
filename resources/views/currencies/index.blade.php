@extends('layouts.master')

@section('title') {{ l('Currencies') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('currencies/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Currencies') }}
    </h2>        
</div>
{{-- l('culo_1') }} {{ l('culo_2', 'layouts') }} {{ l('culo_3', [], 'layouts') }} {{ l('culo_4', []) --}}
<div id="div_currencies">
   <div class="table-responsive">

@if ($currencies->count())
<table id="currencies" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Currency name')}}</th>
			<th>{{l('ISO Code')}}</th>
			<th>{{l('ISO Code number')}}</th>
			<th>{{l('Symbol')}}</th>
			<th>{{l('Format')}}</th>
			<th>{{l('Exchange rate')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($currencies as $currency)
		<tr>
			<td>{{ $currency->id }}</td>
			<td>{{ $currency->name }}</td>
			<td>{{ $currency->iso_code }}</td>
			<td>{{ $currency->iso_code_num }}</td>
			<td>{{ $currency->sign }}</td>
			<td>{{ $currency->format }}</td>
			<td>{{ $currency->conversion_rate }}</td>

            <td class="text-center">@if ($currency->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<td class="text-right">
                @if (  is_null($currency->deleted_at))
                <!-- a class="btn btn-sm btn-blue" href="{{ URL::to('currencies/' . $currency->id . '/exchange') }}" title="{{l('Show Conversion Rate history')}}"><i class="fa fa-bank"></i></a -->               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('currencies/' . $currency->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('currencies/' . $currency->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Currencies') }} :: ({{$currency->id}}) {{ $currency->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('currencies/' . $currency->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('currencies/' . $currency->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
