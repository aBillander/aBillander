@extends('layouts.master')

@section('title') {{ l('Taxes - Create') }} :: @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Tax') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'taxes.store')) !!}

					@include('taxes._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection