@extends('layouts.master')

@section('title') {{ l('Measure Units - Create') }} :: @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $product->name }}</strong> :: {{ l('New Measure Unit') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('products.measureunits.store', $product->id))) !!}

					@include('product_measure_units._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection