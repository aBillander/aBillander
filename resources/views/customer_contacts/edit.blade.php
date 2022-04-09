@extends('layouts.master')

@section('title') {{ l('Contacts - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
		          <h3 class="panel-title">{{ l('Edit Contact', 'contacts') }}: <strong>{{$contact->full_name}}</strong></h3>
		          <h3 class="panel-title" style="margin-top:10px;">{{ l('Owned by', 'contacts') }}: ({{$customer->id}}) {{$customer->name_regular}}</h3>
	      	</div>
			<div class="panel-body"> 

        		@include('errors.list')

				{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('customers.contacts.update', $customer->id, $contact->id), 'name' => 'update_address', 'class' => 'form')) !!}

         			@include('customer_contacts._form')

				{!! Form::close() !!}

			</div>
		</div>
	</div>
</div>

@endsection