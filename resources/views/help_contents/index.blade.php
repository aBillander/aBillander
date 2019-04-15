@extends('layouts.master')

@section('title') {{ l('Help Contents') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('helpcontents/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Help Contents') }}
    </h2>        
</div>

<div id="div_help_contents">
   <div class="table-responsive">

@if ($contents->count())
<table id="help_contents" class="table table-hover">
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
	@foreach ($contents as $content)
		<tr>
			<td>{{ $content->id }}</td>
			<td>{{ $content->name }}</td>
            <td>{{ $content->iso_code }}</td>
            
            <td class="text-center">@if ($content->contains_states) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            
            <td class="text-center">@if ($content->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<td class="text-right">
                @if (  is_null($content->deleted_at))
                <a class="btn btn-sm btn-blue" href="{{ URL::to('helpcontents/' . $content->id . '/states') }}" title="{{l('Show States')}}"><i class="fa fa-folder-open-o"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('helpcontents/' . $content->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('helpcontents/' . $content->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Help Contents') }} :: ({{$content->id}}) {{ $content->name }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('helpcontents/' . $content->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('helpcontents/' . $content->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
