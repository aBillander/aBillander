@extends('layouts.master')

@section('title')  {{ l('Languages') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{{ URL::to('languages/create') }}}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Languages') }}
    </h2>        
</div>

<div id="div_languages">
   <div class="table-responsive">

@if ($languages->count())
<table id="languages" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Language name')}}</th>
            <th class="text-left">{{l('ISO Code')}}</th>
            <th class="text-left">{{l('Language Code')}}</th>
            <th class="text-left">{{l('Date format')}} :: {{l('View')}}</th>
            <th class="text-left">{{l('Date format (full)')}} :: {{l('View')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($languages as $language)
        <tr>
            <td>{{ $language->id }}</td>
            <td>{{ $language->name }}</td>
            <td>{{ $language->iso_code }}</td>
            <td>{{ $language->language_code }}</td>
            <td>{{ $language->date_format_lite }} :: {{ $language->date_format_lite_view }}</td>
            <td>{{ $language->date_format_full }} :: {{ $language->date_format_full_view }}</td>

            <td class="text-center">@if ($language->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-left">
                @if (  is_null($language->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('languages/' . $language->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if (!($language->iso_code==\Config::get('app.fallback_locale')))
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('languages/' . $language->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Languages') }} :: ({{$language->id}}) {{{ $language->name }}} ?" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
                @else
                <a class="btn btn-warning" href="{{ URL::to('languages/' . $language->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('languages/' . $language->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
