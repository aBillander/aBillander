@extends('layouts.master')

@section('title') {{ l('States - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $country->name }}</strong> :: {{ l('New State') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => array('countries.states.store', $country->id))) !!}
				<!-- input type="hidden" value="{{$country->id}}" name="option_group_id" -->

					@include('states._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection