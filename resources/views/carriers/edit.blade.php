@extends('layouts.master')

@section('title') {{ l('Carriers - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Carrier') }} :: ({{$carrier->id}}) {{$carrier->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($carrier, array('method' => 'PATCH', 'route' => array('carriers.update', $carrier->id))) !!}

					@include('carriers._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection