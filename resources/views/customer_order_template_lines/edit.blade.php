@extends('layouts.master')

@section('title') {{ l('Customer Order Template Lines - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Customer Order Template Line') }} :: ({{$customerordertemplateline->id}}) </h3></div>
			<div class="panel-body">
				{!! Form::model($customerordertemplateline, array('method' => 'PATCH', 'route' => array('customerordertemplates.customerordertemplatelines.update', $customerordertemplate->id, $customerordertemplateline->id))) !!}

					@include('customer_order_template_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection