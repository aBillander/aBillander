@extends('layouts.master')

@section('title') {{ l('Shipping Method Rules - Create') }} :: @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $shippingmethod->name }}</strong> :: {{ l('New Shipping Method Rule') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('shippingmethods.shippingmethodrules.store', $shippingmethod->id))) !!}

					@include('shipping_method_rules._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection