@extends('layouts.master')

@section('title') {{ l('Conversion Rates') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right">
        <a href="{{ URL::to('currencies') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Currencies') }}</a>
    </div>
    <h2>
    	<a href="{{ URL::to('currencies') }}">{{ l('Currencies') }}</a> <span style="color: #cccccc;">/</span> <a href="{{ URL::to('currencies/'.$currency->id.'/edit') }}">{{ $currency->name }}</a> <span style="color: #cccccc;">/</span> {{ l('Conversion Rates') }}
    </h2>
</div>

<div id="div_ccrs">
   <div class="table-responsive">

@if ($ccrs->count())
<table id="ccrs" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Date')}}</th>
			<th>{{l('Exchange rate')}}</th>
			<th>{{l('User')}}</th>
		</tr>
	</thead>
	<tbody>
		<tr style="color: #3a87ad; background-color: #d9edf7;">
			<td> </td>
			<td>{{l('Current')}}</td>
			<td>{{ $currency->conversion_rate }}</td>
			<td> </td>
		</tr>
	@foreach ($ccrs as $cc)
		<tr>
			<td>{{ $cc->id }}</td>
			<td>{{ $cc->date }}</td>
			<td>{{ $cc->conversion_rate }}</td>
			<td>[{{ $cc->user->name }}] {{ $cc->user->getFullName() }}</td>
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
