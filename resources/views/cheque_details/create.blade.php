@extends('layouts.master')

@section('title') {{ l('Customer Cheque Details - Create') }} :: @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><span class="lead well well-sm alert-warning">{{$cheque->document_number}}</span> :: {{ l('New Customer Cheque Detail') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('cheques.chequedetails.store', $cheque->id))) !!}

					@include('cheque_details._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection