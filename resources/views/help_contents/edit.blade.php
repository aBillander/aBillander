@extends('layouts.master')

@section('title') {{ l('Countries - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Country') }} :: ({{$country->id}}) {{$country->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($country, array('method' => 'PATCH', 'route' => array('countries.update', $country->id))) !!}

					@include('countries._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop