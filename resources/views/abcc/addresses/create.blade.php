@extends('abcc.layouts.master')

@section('title') {{ l('Addresses - Create', 'addresses') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">{{ l('New Address', 'addresses') }} :: {{$customer->name_fiscal}}</h3>
			</div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => array('abcc.customer.addresses.store'), 'name' => 'create_address', 'class' => 'form')) !!}

					@include('abcc.addresses._form')

				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>

@endsection