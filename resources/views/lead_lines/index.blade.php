@extends('layouts.master')

@section('title') {{ l('Lead Lines') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('leads/'.$lead->id.'/leadlines/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ URL::to('leads') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Leads') }}</a> 
    </div>
    <h2>
        <a href="{{ URL::to('leads') }}">{{ l('Leads') }}</a> <span style="color: #cccccc;">/</span> {{ $lead->name }}
    </h2>
    <h2>
        <span class="lead well well-sm">

            {{ $lead->party->name_commercial }} &nbsp;

         <a href="{{ route('parties.edit', $lead->party->id) }}" class="btn btn-xs btn-warning" title="{{ l('Go to', 'layouts') }}" target="_blank"><i class="fa fa-external-link"></i></a>

         </span>
    </h2>        
</div>

<div id="div_leadlines">
   <div class="table-responsive">

@if ($leadlines->count())
<table id="leadlines" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <!-- th>{{l('Position')}}</th -->
            <th>{{l('Name')}}</th>
            <th>{{l('Status', 'layouts')}}</th>
            <!-- th class="text-center">{{l('Description')}}</th -->
            <th>{{l('Start')}} /<br />{{l('Finish')}}</th>
            <th>{{l('Due')}}</th>
            <th class="text-center">{{l('Results')}}</th>
            <th>{{l('Assigned to')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($leadlines as $leadline)
		<tr>
            <td>{{ $leadline->id }}</td>
            <!-- td>{{ $leadline->position }}</td -->
            <td><strong>{{ $leadline->name }}</strong><br />
                {!! $leadline->description !!}

            </td>

            <td>{{ $leadline->status_name }}</td>

            <td>{{ abi_date_short($leadline->start_date) }}<br />{{ abi_date_short($leadline->finish_date) }}</td>
            <td>{{ abi_date_short($leadline->due_date) }}</td>

            <td width="40%">{!! $leadline->results !!}
            </td>

            <td>{{ $leadline->assignedto->full_name }}</td>

			<td class="text-right button-pad">
                @if (  is_null($leadline->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('leads/' . $lead->id.'/leadlines/' . $leadline->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('leads/' . $lead->id.'/leadlines/' . $leadline->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Lead Lines') }} :: ({{$leadline->id}}) {{{ $leadline->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('leadlines/' . $leadline->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('leadlines/' . $leadline->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
