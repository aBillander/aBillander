@extends('layouts.master')

@section('title') {{ l('Leads') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('leads/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Leads') }}
    </h2>        
</div>

<div id="div_leads">
   <div class="table-responsive">

@if ($leads->count())
<table id="leads" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Lead Name')}}</th>
            <th>{{l('Party')}}</th>
            <th>{{l('Status', 'layouts')}}</th>
            <th class="text-center">{{l('Description')}}</th>
            <th>{{l('Lead Date')}}</th>
            <th>{{l('Lead End Date')}}</th>
            <th>{{l('Assigned to')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($leads as $lead)
		<tr>
            <td>{{ $lead->id }}</td>
            <td>{{ $lead->name }}</td>
			<td>
@if ( $lead->party )
                <a href="{{ route('parties.edit', $lead->party->id) }}" target="_party" title="{{l('Go to', [], 'layouts')}}">{{ $lead->party->name_commercial }}</a>
@else
                -
@endif
            </td>
            <td>{{ $lead->status_name }}</td>

            <td class="text-center">@if ($lead->description)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($lead->description) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>

            <td>{{ abi_date_short($lead->lead_date) }}</td>
            <td>{{ abi_date_short($lead->lead_end_date) }}</td>
            <td>{{ $lead->assignedto->full_name }}</td>

            <td class="text-center">@if ($lead->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($lead->notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>

			<td class="text-right">
                @if (  is_null($lead->deleted_at))
                <a class="btn btn-sm btn-blue" href="{{ URL::to('leads/' . $lead->id . '/leadlines') }}" title="{{l('Show Lead Lines')}}"><i class="fa fa-folder-open-o"></i></a>
                <a class="btn btn-sm btn-warning" href="{{ URL::to('leads/' . $lead->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('leads/' . $lead->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Leads') }} :: ({{$lead->id}}) {{ $lead->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('leads/' . $lead->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('leads/' . $lead->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
