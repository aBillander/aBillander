@extends('layouts.master')

@section('title') {{ l('Users') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{{ URL::to('users/create') }}}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Users') }}
    </h2>        
</div>

<div id="div_users">
   <div class="table-responsive">

@if ($users->count())
<table id="users" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('User name')}}</th>
            <th class="text-left">{{l('Full Name')}}</th>
            <th class="text-left">{{l('Email')}}</th>
            <th class="text-left">{{l('User home page')}}</th>
            <th class="text-left">{{l('Language')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-center">{{l('Is Administrator?')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->getFullName() }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->home_page }}</td>
            <td>{{ $user->language->name }}</td>

            <td class="text-center">@if ($user->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-center">@if ($user->is_admin) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
    
            <td class="text-left">
                @if (  is_null($user->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('users/' . $user->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if (!$user->is_admin)
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('users/' . $user->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Users') }} :: ({{$user->id}}) {{ $user->getFullName() }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
                @else
                <a class="btn btn-warning" href="{{ URL::to('users/' . $user->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('users/' . $user->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
