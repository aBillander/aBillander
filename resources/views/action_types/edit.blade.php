@extends('layouts.master')

@section('title') {{ l('Action Types - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Action Type') }} :: ({{$actiontype->id}}) {{$actiontype->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($actiontype, array('method' => 'PATCH', 'route' => array('actiontypes.update', $actiontype->id))) !!}

					@include('action_types._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection