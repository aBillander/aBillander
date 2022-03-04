@extends('layouts.master')

@section('title') {{ l('Taxes - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Tax') }} :: ({{$tax->id}}) {{$tax->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($tax, array('method' => 'PATCH', 'route' => array('taxes.update', $tax->id))) !!}

					@include('taxes._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection