@extends('layouts.master')

@section('title') {{ l('Users - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit User') }} :: ({{$user->id}}) {{$user->getFullName()}}</h3></div>
			<div class="panel-body">

        @include('errors.list')

				{!! Form::model($user, array('method' => 'PATCH', 'route' => array('users.update', $user->id))) !!}

          @include('users._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop