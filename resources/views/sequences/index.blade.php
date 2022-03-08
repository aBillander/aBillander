@extends('layouts.master')

@section('title') {{ l('Sequences') }} @parent @endsection


@section('content')
<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('sequences/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Sequences') }}
    </h2>        
</div>

<!-- div class="col-md-12" style="margin-top: 20px">
	<h4 style="margin-top: 40px">Document Sequences</h4>
	{{ link_to_route('sequences.create', 'Add new Document Sequence', null, array('class' => 'btn btn-primary')) }}
</div -->

<div id="div_sequences">
   <div class="table-responsive">

@if($sequences->count())
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{{l('ID', [], 'layouts')}}</th>
					<th>{{l('Sequence name')}}</th>
					<th>{{l('Document type')}}</th>
					<th>{{l('Format')}}</th>
					<th>{{l('Next ID')}}</th>
					<th>{{l('Last Date Used')}}</th>
					<th class="text-center">{{l('Active', [], 'layouts')}}</th>
					<th> </th>
				</tr>
			</thead>
			<tbody>
				@foreach($sequences as $sequence)
				<tr>
					<td>{{ $sequence->id }}</td>
					<td>{{ $sequence->name }}</td>
					<td>{{ \App\Sequence::getTypeName($sequence->model_name) }}</td>
					<td>{{ $sequence->format }}</td>
					<td>{{ $sequence->next_id }}</td>
					<td>@if (  is_null($sequence->last_date_used))
							-
						@else
							{{ abi_date_short( $sequence->last_date_used, AbiContext::getContext()->language->date_format_lite ) }}
						@endif
					</td>
					<td class="text-center">
						@if ($sequence->active > 0)
							<a href="" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></a>
						@else
							<a href="" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></a>
						@endif
					</td>
					
					<td class="text-right">
		                @if (  is_null($sequence->deleted_at))
		                <a class="btn btn-sm btn-warning" href="{{ URL::to('sequences/' . $sequence->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
		                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
		                		href="{{ URL::to('sequences/' . $sequence->id ) }}" 
		                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
		                		data-title="{{ l('Sequences') }} :: ({{$sequence->id}}) {{ $sequence->name }} " 
		                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
		                @else
		                <a class="btn btn-warning" href="{{ URL::to('sequences/' . $sequence->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
		                <a class="btn btn-danger" href="{{ URL::to('sequences/' . $sequence->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
