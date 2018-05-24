@extends('layouts.master')

@section('title') {{ l('Options - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Option') }} :: ({{$option->id}}) {{$option->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($option, array('method' => 'PATCH', 'route' => array('optiongroups.options.update', $optiongroup->id, $option->id))) !!}

					@include('options._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop