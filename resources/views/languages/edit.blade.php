@extends('layouts.master')

@section('title') {{ l('Languages - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Language') }} :: ({{$language->id}}) {{$language->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($language, array('method' => 'PATCH', 'route' => array('languages.update', $language->id))) !!}

					@include('languages._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection