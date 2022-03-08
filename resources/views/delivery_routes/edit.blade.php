@extends('layouts.master')

@section('title') {{ l('Delivery Routes - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Delivery Route') }} :: ({{$deliveryroute->id}}) {{$deliveryroute->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($deliveryroute, array('method' => 'PATCH', 'route' => array('deliveryroutes.update', $deliveryroute->id))) !!}

					@include('delivery_routes._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection