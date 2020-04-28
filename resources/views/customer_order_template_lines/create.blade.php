@extends('layouts.master')

@section('title') {{ l('Customer Order Template Lines - Create') }} :: @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $customerordertemplate->name }}</strong> :: {{ l('New Customer Order Template Line') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('customerordertemplates.customerordertemplatelines.store', $customerordertemplate->id))) !!}

					@include('customer_order_template_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection