@extends('layouts.master')

@section('title') {{ l('Suppliers - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Supplier') }} :: ({{$supplier->id}}) {{$supplier->name_commercial}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($supplier, array('method' => 'PATCH', 'route' => array('suppliers.update', $supplier->id))) !!}

					@include('suppliers._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop