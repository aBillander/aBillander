@extends('layouts.master')

@section('title') {{ l('Countries') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('countries/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Countries') }}
    </h2>        
</div>

<div id="div_countries">
   <div class="table-responsive">

@if ($countries->count())
<table id="countries" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Country name')}}</th>
            <th>{{l('Country ISO code')}}</th>
            <th class="text-center">{{l('Contain States')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($countries as $country)
		<tr>
			<td>{{ $country->id }}</td>
			<td>{{ $country->name }}</td>
            <td>{{ $country->iso_code }}</td>
            
            <td class="text-center">@if ($country->contains_states) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            
            <td class="text-center">@if ($country->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<td class="text-right">
                @if (  is_null($country->deleted_at))
                <a class="btn btn-sm btn-blue" href="{{ URL::to('countries/' . $country->id . '/states') }}" title="{{l('Show States')}}"><i class="fa fa-folder-open-o"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('countries/' . $country->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('countries/' . $country->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Countries') }} :: ({{$country->id}}) {{ $country->name }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('countries/' . $country->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('countries/' . $country->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
