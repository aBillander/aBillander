@extends('layouts.master')

@section('title') {{ l('Measure Units - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Measure Unit') }} :: ({{$measureunit->id}}) {{$measureunit->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($measureunit, array('method' => 'PATCH', 'route' => array('measureunits.update', $measureunit->id))) !!}

					@include('measure_units._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection