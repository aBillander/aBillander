@extends('layouts.master')

@section('title') {{ l('States - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit State') }} :: ({{$state->id}}) {{$state->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($state, array('method' => 'PATCH', 'route' => array('countries.states.update', $country->id, $state->id))) !!}

					@include('states._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop