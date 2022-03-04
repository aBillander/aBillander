@extends('layouts.master')

@section('title') {{ l('Delivery Route Lines - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Delivery Route Line') }} :: ({{$deliveryrouteline->id}}) </h3></div>
			<div class="panel-body">
				{!! Form::model($deliveryrouteline, array('method' => 'PATCH', 'route' => array('deliveryroutes.deliveryroutelines.update', $deliveryroute->id, $deliveryrouteline->id))) !!}

					@include('delivery_route_lines._form_edit')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection