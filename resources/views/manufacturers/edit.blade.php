@extends('layouts.master')

@section('title') {{ l('Manufacturers - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Manufacturer') }} :: ({{$manufacturer->id}}) {{$manufacturer->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($manufacturer, array('method' => 'PATCH', 'route' => array('manufacturers.update', $manufacturer->id))) !!}

					@include('manufacturers._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection