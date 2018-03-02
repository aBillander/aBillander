@extends('layouts.master')

@section('title') {{ l('Configuration Keys') }} @parent @stop

@section('content')
<div class="page-header">
    <div class="pull-right">
        <a href="{{{ URL::to('configurations/create') }}}" class="btn btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ URL::to('configurationkeys') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Configurations') }}</a>
    </div>
    <h2>
        {{ l('Configuration Keys') }}
    </h2>        
</div>

<div id="div_configurations" class="col-md-12">
   <div class="table-responsive">

@if($configurations->count())
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{{l('ID', [], 'layouts')}}</th>
					<th width="45%">


{!! link_to_route('configurations.index',l('Name'), array('sort' => 'name')) !!}
@if($order!='asc')
<a href="{{ route('configurations.index', array('sort' => $sort, 'order' => 'asc')) }}">
        <i class="dropup caret" style="border-top: 0 dotted; border-bottom: 4px solid #000; content: "";"></i>
</a>
@else
<a href="{{ route('configurations.index', array('sort' => $sort, 'order' => 'desc')) }}">
        <i class="caret"></i>
</a>
@endif


					</th>
					<th>{{l('Value')}}</th>
					<th>{{l('Date Updated')}}</th>
					<th width="10%"> </th>
				</tr>
			</thead>
			<tbody>
				@foreach($configurations as $configuration)
				<tr>
					<td>{{ $configuration->id }}</td>
					<td><strong>{{ $configuration->name }}</strong><br />{{ $configuration->description }}</td>
					<td>{{ $configuration->value }}</td>
					<td>{{ $configuration->updated_at }}</td>
					<td  style="width:1px; white-space: nowrap;">
						<a class="btn btn-sm btn-warning" href="{{ URL::to('configurations/' . $configuration->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                		<a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
		                		href="{{ URL::to('configurations/' . $configuration->id ) }}" 
		                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
		                		data-title="{{ l('Configurations') }} :: ({{$configuration->id}}) {{ $configuration->name }} " 
		                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{-- $configurations->links() --}}
	</div>
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