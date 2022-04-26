@extends('layouts.master')

@section('title') {{ l('Action Types - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Action Type') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'actiontypes.store')) !!}

					@include('action_types._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection