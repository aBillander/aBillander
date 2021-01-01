@extends('layouts.master')

@section('title') {{ l('Parties') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('parties/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Parties') }}
    </h2>        
</div>

<div id="div_parties">
   <div class="table-responsive">

@if ($parties->count())
<table id="parties" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Type')}}</th>
            <th>{{l('Commercial Name')}}</th>
            <th>{{l('Email')}}</th>
            <th>{{l('Phone (regular)')}}</th>
            <th>{{l('Phone (mobile)')}}</th>
            <th>{{l('Assigned to')}}</th>
            <th class="text-center">{{l('Blocked', [], 'layouts')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($parties as $party)
		<tr>
            <td>{{ $party->id }}</td>
            <td>{{ $party->type_name }}</td>
			<td>{{ $party->name_commercial }}</td>
            <td>{{ $party->email }}</td>
            <td>{{ $party->phone }}</td>
            <td>{{ $party->phone_mobile }}</td>
            <td>{{ $party->assignedto->full_name }}</td>

            <td class="text-center">@if ($party->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>

            <td class="text-center">@if ($party->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-center">@if ($party->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($party->notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>

			<td class="text-right">
                @if (  is_null($party->deleted_at))
                <a class="btn btn-sm btn-grey" href="{{ URL::to('contacts/parties/' . $party->id ) }}" title="{{l('Show Contacts')}}"><i class="fa fa-address-book-o"></i></a>

                <a class="btn btn-sm btn-blue" href="{{ URL::to('leads/parties/' . $party->id ) }}" title="{{l('Show Leads')}}"><i class="fa fa-magic"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('parties/' . $party->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('parties/' . $party->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Parties') }} :: ({{$party->id}}) {{{ $party->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('parties/' . $party->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('parties/' . $party->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
