@extends('layouts.master')

@section('title') {{ l('Contacts - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Contact') }} :: ({{$contact->id}}) {{$contact->full_name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('contacts.update', $contact->id))) !!}

					@include('contacts._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop