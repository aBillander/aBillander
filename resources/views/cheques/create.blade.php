@extends('layouts.master')

@section('title') {{ l('Parties - Create') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Party') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'parties.store')) !!}

					@include('parties._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop