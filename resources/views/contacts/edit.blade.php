@extends('layouts.master')

@section('title') {{ l('Contacts - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Contact') }} :: 
               <span class="lead well well-sm">

            {{ $contact->party->name_commercial }} &nbsp;
            <a href="{{ route('parties.edit', $contact->party->id) }}" class="btn btn-xs btn-warning" title="{{ l('Go to', 'layouts') }}" target="_blank"><i class="fa fa-external-link"></i></a> 

	         </span> 
	         ({{$contact->id}}) {{$contact->full_name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('contacts.update', $contact->id))) !!}

					@include('contacts._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection