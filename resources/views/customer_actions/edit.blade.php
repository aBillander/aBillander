@extends('layouts.master')

@section('title') {{ l('Actions - Edit', 'actions') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
		          <h3 class="panel-title">{{ l('Edit Action', 'actions') }}: <strong>{{$action->full_name}}</strong></h3>
		          <h3 class="panel-title" style="margin-top:10px;">{{ l('Owned by', 'actions') }}: ({{$customer->id}}) {{$customer->name_regular}}</h3>
	      	</div>
			<div class="panel-body"> 

        		@include('errors.list')

				{!! Form::model($action, array('method' => 'PATCH', 'route' => array('customers.actions.update', $customer->id, $action->id), 'name' => 'update_action', 'class' => 'form')) !!}

         			@include('customer_actions._form')

				{!! Form::close() !!}

			</div>
		</div>
	</div>
</div>

@endsection