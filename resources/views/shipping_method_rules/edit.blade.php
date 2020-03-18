@extends('layouts.master')

@section('title') {{ l('Shipping Method Rules - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $shippingmethod->name }}</strong> :: {{ l('Edit Shipping Method Rule') }} :: ({{$rule->id}}) </h3></div>
			<div class="panel-body">
				{!! Form::model($rule, array('method' => 'PATCH', 'route' => array('shippingmethods.shippingmethodrules.update', $shippingmethod->id, $rule->id))) !!}

					@include('shipping_method_rules._form_edit')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection