@extends('layouts.master')

@section('title') {{ l('Down Payment to Suppliers - Create') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Down Payment to Supplier') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'supplier.downpayments.store')) !!}

					@include('supplier_down_payments._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop