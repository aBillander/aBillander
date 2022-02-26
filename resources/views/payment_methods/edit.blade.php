@extends('layouts.master')

@section('title') {{ l('Payment Methods - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Payment Method') }} :: ({{$paymentmethod->id}}) {{$paymentmethod->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($paymentmethod, array('method' => 'PATCH', 'route' => array('paymentmethods.update', $paymentmethod->id), 'onsubmit' => 'return checkFields();')) !!}

					@include('payment_methods._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')

    @include('payment_methods.js.create_method_js')

@endsection