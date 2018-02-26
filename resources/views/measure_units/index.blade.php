@extends('layouts.master')

@section('title') {{ l('Measure Units') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('measureunits/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Measure Units') }}
    </h2>        
</div>

<div id="div_measureunits">
   <div class="table-responsive">

@if ($measureunits->count())
<table id="measureunits" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Symbol')}}</th>
			<th>{{l('Measure Unit name')}}</th>
			<th>{{l('Type')}}</th>
			<th>{{l('Decimal places')}}</th>
			<!-- th>{{l('Exchange rate')}}</th -->
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($measureunits as $measureunit)
		<tr>
			<td>{{ $measureunit->id }}</td>
			<td>{{ $measureunit->sign }}</td>
			<td>{{ $measureunit->name }}</td>
			<td>{{ \App\MeasureUnit::getTypeList()[$measureunit->type] }}</td>
			<td>{{ $measureunit->decimalPlaces }}</td>
			<!-- td>{{ $measureunit->conversion_rate }}</td -->

            <td class="text-center">@if ($measureunit->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<td class="text-right">
                @if (  is_null($measureunit->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('measureunits/' . $measureunit->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('measureunits/' . $measureunit->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Measure Units') }} :: ({{$measureunit->id}}) {{ $measureunit->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('measureunits/' . $measureunit->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('measureunits/' . $measureunit->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
