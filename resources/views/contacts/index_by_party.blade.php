@extends('layouts.master')

@section('title') {{ l('Contacts') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ route('contacts.create.withparty', $party->id) }}" class=" hide  btn btn-sm btn-success" 
        		title="{{l('Add New', [], 'layouts')}}"><i class="fa fa-plus"></i> &nbsp;{{l('Add New Contact to :party', ['party' => $party->name_commercial])}}</a>

        <a href="{{ route('contacts.create.withparty', $party->id) }}" class="btn xbtn-sm btn-success" 
                title="{{l('Add New', [], 'layouts')}}"><i class="fa fa-plus"></i> &nbsp;{{l('Add New Contact')}}</a>

                <a href="{{ route('parties.index') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Parties') }}</a>
    </div>
    <h2>
        <a href="{{ URL::to('contacts') }}">{{ l('Contacts') }}</a> <span style="color: #cccccc;">::</span> 
                <span class="lead well well-sm">

                    {{ $party->name_commercial }} &nbsp;

                 <a href="{{ route('parties.edit', $party->id) }}" class="btn btn-xs btn-warning" title="{{ l('Go to', 'layouts') }}" target="_blank"><i class="fa fa-external-link"></i></a>

                 </span>
    </h2>        
</div>

<div id="div_contacts">
   <div class="table-responsive">

@if ($contacts->count())
<table id="contacts" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Contact Name')}}</th>
            <!-- th>{{l('Party')}}</th-->
            <th>{{l('Email')}}</th>
            <th>{{l('Phone (regular)')}}</th>
            <th>{{l('Phone (mobile)')}}</th>
            <th class="text-center">{{l('Blocked', [], 'layouts')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($contacts as $contact)
		<tr>
            <td>{{ $contact->id }}</td>
            <td>{{ $contact->firstname }} {{ $contact->lastname }}<br />{{ $contact->job_title }}</td>
			<!-- td>
@if ( $contact->party )
                <a href="{{ route('parties.edit', $contact->party->id) }}" target="_party" title="{{l('Go to', [], 'layouts')}}">{{ $contact->party->name_commercial }}</a>
@else
                -
@endif
            </td -->
            <td>{{ $contact->email }}</td>
            <td>{{ $contact->phone }}</td>
            <td>{{ $contact->phone_mobile }}</td>

            <td class="text-center">@if ($contact->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>

            <td class="text-center">@if ($contact->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-center">@if ($contact->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($contact->notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>

			<td class="text-right">
                @if (  is_null($contact->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('contacts/' . $contact->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('contacts/' . $contact->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Contacts') }} :: ({{$contact->id}}) {{{ $contact->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('contacts/' . $contact->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('contacts/' . $contact->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
