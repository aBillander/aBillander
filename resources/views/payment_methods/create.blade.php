@extends('layouts.master')

@section('title') {{ l('Payment Methods - Create') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Payment Method') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{{-- Form::open(array('route' => 'paymentmethods.store')) --}}
				{!! Form::model($paymentmethod, array('method' => 'POST', 'route' => array('paymentmethods.store'), 'onsubmit' => 'return checkFields();')) !!}

					@include('payment_methods._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop

@section('scripts')

    @include('payment_methods.js.create_method_js')

@stop