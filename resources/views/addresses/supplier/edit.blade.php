@extends('layouts.master')

@section('title') {{ l('Addresses - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
		          <h3 class="panel-title">{{ l('Edit Address', 'addresses') }}: ({{$address->id}}) {{$address->alias}}</h3>
		          <h3 class="panel-title" style="margin-top:10px;">{{ l('Owned by', 'addresses') }}: ({{$supplier->id}}) {{$supplier->name_regular}}</h3>
	      	</div>
			<div class="panel-body"> 

        		@include('errors.list')

				{!! Form::model($address, array('method' => 'PATCH', 'route' => array('suppliers.addresses.update', $supplier->id, $address->id), 'name' => 'update_address', 'class' => 'form')) !!}

         			@include('addresses._form')

				{!! Form::close() !!}

			</div>
		</div>
	</div>
</div>

@stop