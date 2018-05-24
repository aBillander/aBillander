@extends('layouts.master')

@section('title') {{ l('Images - Create') }} :: @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Image') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'images.store', 'class' => 'form', 'files' => true)) !!}

					@include('images._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop