@extends('layouts.master')

@section('title') {{ l('Parties - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Party') }} :: ({{$party->id}}) {{$party->full_name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($party, array('method' => 'PATCH', 'route' => array('parties.update', $party->id))) !!}

					@include('parties._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop