@extends('layouts.master')

@section('title') {{ l('Production Sheets - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Production Sheet') }} :: ({{$sheet->id}}) {{$sheet->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($sheet, array('method' => 'PATCH', 'route' => array('productionsheets.update', $sheet->id))) !!}

					@include('production_sheets._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop