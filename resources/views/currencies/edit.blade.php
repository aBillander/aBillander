@extends('layouts.master')

@section('title') {{ l('Currencies - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Currency') }} :: ({{$currency->id}}) {{$currency->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($currency, array('method' => 'PATCH', 'route' => array('currencies.update', $currency->id))) !!}

					@include('currencies._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection