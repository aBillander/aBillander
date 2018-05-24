@extends('layouts.master')

@section('title') {{ l('Translations') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <!-- a href="{{ URL::to('translations/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a -->
    </div>
    <h2>
        {{ l('Translations') }} :: {{$language->name}}
    </h2>        
</div>

<div id="div_translations">
   <div class="table-responsive">

@if ($views)
<table id="translations" class="table table-hover">
	<thead>
		<tr>
			<th>{{l('resources/views')}}</th>
			<th>{{l('Translation name')}}</th>
			<th>{{l('Translation file')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($views as $view)
		<tr>
			<td> </td>
			<td>{{ $view }}</td>
			<td>{{ isset($translations[str_replace('_', '', $view).'.php']) ? $translations[str_replace('_', '', $view).'.php'] : '' }}</td>

			<td class="text-right">
                
                <a class="btn btn-sm btn-warning" href="{{ URL::to('translations/' . $view . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                
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
