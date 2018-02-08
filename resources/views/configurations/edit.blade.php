@extends('layouts.master')

@section('title') {{ l('Configuration Keys - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Configuration Key') }} :: ({{$configuration->id}}) {{$configuration->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($configuration, array('method' => 'PATCH', 'route' => array('configurations.update', $configuration->id))) !!}

					@include('configurations._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop