@extends('layouts.master')

@section('title') {{ l('Payment Types - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Payment Type') }} :: ({{$paymenttype->id}}) {{$paymenttype->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($paymenttype, array('method' => 'PATCH', 'route' => array('paymenttypes.update', $paymenttype->id))) !!}

					@include('payment_types._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop