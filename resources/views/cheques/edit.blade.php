@extends('layouts.master')

@section('title') {{ l('Customer Cheques - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-10 col-md-offset-1" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Customer Cheque') }} :: ({{$cheque->id}}) {{$cheque->document_number}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($cheque, array('method' => 'PATCH', 'route' => array('cheques.update', $cheque->id))) !!}

					@include('cheques._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection