@extends('layouts.master')

@section('title') {{ l('Option Groups - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Option Group') }} :: ({{$optiongroup->id}}) {{$optiongroup->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($optiongroup, array('method' => 'PATCH', 'route' => array('optiongroups.update', $optiongroup->id))) !!}

					@include('option_groups._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection