@extends('layouts.master')

@section('title') {{ l('Ecotaxes - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Ecotax') }} :: ({{$ecotax->id}}) {{$ecotax->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($ecotax, array('method' => 'PATCH', 'route' => array('ecotaxes.update', $ecotax->id))) !!}

					@include('ecotaxes._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop