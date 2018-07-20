@extends('layouts.master')

@section('title') {{ l('Shipping Methods - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Shipping Method') }} :: ({{$shippingmethod->id}}) {{$shippingmethod->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($shippingmethod, array( 'method' => 'PATCH', 'route' => array('shippingmethods.update', $shippingmethod->id) )) !!}

					@include('shipping_methods._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop
