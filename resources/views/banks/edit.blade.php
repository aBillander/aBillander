@extends('layouts.master')

@section('title') {{ l('Banks - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Bank') }} :: ({{$bank->id}}) {{$bank->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($bank, array('method' => 'PATCH', 'route' => array('banks.update', $bank->id))) !!}

					@include('banks._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop