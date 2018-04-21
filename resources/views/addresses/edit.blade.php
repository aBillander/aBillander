@extends('layouts.master')

@section('title') {{ l('Addresses - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
		          <h3 class="panel-title">Modificar Dirección: ({{$address->id}}) {{$address->alias}}</h3>
		          <h3 class="panel-title" style="margin-top:10px;">Pertenece a: ({{$customer->id}}) {{$customer->name_fiscal}}</h3>
	      	</div>
			<div class="panel-body"> 

        		@include('errors.list')

				{!! Form::model($address, array('method' => 'PATCH', 'route' => array('customers.addresses.update', $customer->id, $address->id), 'name' => 'update_address', 'class' => 'form')) !!}

         			@include('addresses._form')

				{!! Form::close() !!}

			</div>
		</div>
	</div>
</div>

@stop