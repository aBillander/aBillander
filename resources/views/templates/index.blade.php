@extends('layouts.master')

@section('title') {{ l('Templates') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a href="{{ URL::to('templates/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <button type="button" class="btn btn-sm btn-info" 
        		data-toggle="modal" data-target="#templatesHelp"
        		title="{{l('Help', [], 'layouts')}}"><i class="fa fa-medkit"></i> {{l('Help', [], 'layouts')}}</button>

    </div>
    <h2>
        {{ l('Templates') }}
    </h2>        
</div>

<div id="div_templates">
   <div class="table-responsive">

@if ($templates->count())
<table id="templates" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Template name')}}</th>
			<th>{{l('Document type')}}</th>
			<th>{{l('Folder')}}</th>
			<th>{{l('File name')}}</th>
			<th>{{l('Paper')}}</th>
			<th>{{l('Orientation')}}</th>
            <!-- th class="text-center">{{l('Active', [], 'layouts')}}</th -->
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($templates as $template)
		<tr>
			<td>{{ $template->id }}</td>
			<td>{{ $template->name }}</td>
			<td>{{ \App\Template::getTypeName($template->model_name) }}</td>
			<td>{{ $template->folder }}</td>
			<td>{{ $template->file_name }}</td>
			<td>{{ $template->paper }}</td>
			<td>{{ \App\Template::getOrientationName($template->orientation) }}</td>

            <!-- td class="text-center">@if ($template->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td -->

			<td class="text-right">
                @if (  is_null($template->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('templates/' . $template->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('templates/' . $template->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Templates') }} :: ({{$template->id}}) {{ $template->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('templates/' . $template->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('templates/' . $template->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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

@include('templates/_modal_help')
