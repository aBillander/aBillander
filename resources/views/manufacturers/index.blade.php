@extends('layouts.master')

@section('title') {{ l('Manufacturers') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('manufacturers/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Manufacturers') }}
    </h2>        
</div>

<div id="div_manufacturers">
   <div class="table-responsive col-lg-6 col-md-6 col-sm-6">

@if ($manufacturers->count())
<table id="manufacturers" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Manufacturer name')}}</th>
            <!-- th class="text-center">{{l('Active', [], 'layouts')}}</th -->
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($manufacturers as $manufacturer)
		<tr>
			<td>{{ $manufacturer->id }}</td>
			<td>{{ $manufacturer->name }}</td>
{{--
            <td class="text-center">@if ($manufacturer->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
--}}
			<td class="text-right">
                @if (  is_null($manufacturer->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('manufacturers/' . $manufacturer->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('manufacturers/' . $manufacturer->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Manufacturers') }} :: ({{$manufacturer->id}}) {{ $manufacturer->name }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('manufacturers/' . $manufacturer->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('manufacturers/' . $manufacturer->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
