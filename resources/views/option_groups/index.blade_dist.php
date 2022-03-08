@extends('layouts.master')

@section('title') {{ l('Option Groups') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('optiongroups/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Option Groups') }}
    </h2>        
</div>

<div id="div_optiongroups">
   <div class="table-responsive">

@if ($optiongroups->count())
<table id="optiongroups" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Option Group name')}}</th>
			<th>{{l('Group public name')}}</th>
			<th>{{l('Position')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($optiongroups as $optiongroup)
		<tr>
			<td>{{ $optiongroup->id }}</td>
			<td>{{ $optiongroup->name }}</td>
			<td>{{ $optiongroup->public_name }}</td>
			<td>{{ $optiongroup->position }}</td>

			<td class="text-right">
                @if (  is_null($optiongroup->deleted_at))
                <a class="btn btn-sm btn-blue" href="{{ URL::to('optiongroups/' . $optiongroup->id . '/options') }}" title="{{l('Show Options')}}"><i class="fa fa-folder-open-o"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('optiongroups/' . $optiongroup->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('optiongroups/' . $optiongroup->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Option Groups') }} :: ({{$optiongroup->id}}) {{ $optiongroup->name }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('optiongroups/' . $optiongroup->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('optiongroups/' . $optiongroup->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif

			</td>
		</tr>
        <tr>
            <td  style="border-top: 0px solid #dddddd;"></td>
            <td colspan="3" style="border-top: 0px solid #dddddd; padding-top: 0px;">
                <ul class="nav nav-pills">
                @if ($optiongroup->options->count())
                @foreach ($optiongroup->options as $option)
                    <li><span class="label label-primary"> <span class="badge">{{ $option->position }}</span> &nbsp;{{ $option->name }} </span> &nbsp; </li>
                @endforeach
                @else
                    <li class="xalert alert-warning alert-block">
                            <i class="fa fa-warning"></i>
                            {{l('No Options found')}} &nbsp; </li>
                @endif
                    <xli><a class="btn btn-xs btn-info" href="{{ URL::to('optiongroups/'.$optiongroup->id.'/options/create') }}" title="{{l('Add New Option')}}"><i class="fa fa-plus"></i></a> &nbsp; </xli>
                </ul>
            </td>
            <td style="border-top: 0px solid #dddddd;"></td>
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
