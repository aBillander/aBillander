@extends('layouts.master')

@section('title') {{ l('Lots - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Lot') }} :: ({{$lot->id}}) &nbsp; <strong>{{$lot->reference}}</strong></h3></div>
			<div class="panel-body">
				{!! Form::model($lot, array('method' => 'PATCH', 'route' => array('lots.update', $lot->id))) !!}

					@include('lots._form_edit')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection