@extends('layouts.master')

@section('title') {{ l('Configuration Keys - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-4 col-md-offset-4" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Configuration Key') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'configurations.store')) !!}

					@include('configurations._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection