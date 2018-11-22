@extends('layouts.master')

@section('title') {{ l('Todos - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Todo') }} :: ({{$todo->id}}) {{$todo->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($todo, array('method' => 'PATCH', 'route' => array('todos.update', $todo->id))) !!}

					@include('todos._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop