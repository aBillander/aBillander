@extends('layouts.master')

@section('title') {{ l('Delivery Route Lines - Create') }} :: @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $deliveryroute->name }}</strong> :: {{ l('New Delivery Route Line') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('deliveryroutes.deliveryroutelines.store', $deliveryroute->id))) !!}

					@include('delivery_route_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection