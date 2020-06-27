@extends('layouts.master')

@section('title') {{ l('Customer Order Templates - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Customer Order Template') }} :: ({{$cheque->id}}) {{$cheque->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($cheque, array('method' => 'PATCH', 'route' => array('cheques.update', $cheque->id))) !!}

					@include('customer_order_templates._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection