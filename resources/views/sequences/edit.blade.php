@extends('layouts.master')

@section('title') {{ l('Sequences - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Sequence') }} :: ({{$sequence->id}}) {{$sequence->name}} - <span id="sequence_format" class="label label-info"></span></h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($sequence, array('method' => 'PATCH', 'route' => array('sequences.update', $sequence->id))) !!}

					@include('sequences._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection