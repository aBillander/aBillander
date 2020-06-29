@extends('layouts.master')

@section('title') {{ l('Customer Cheque Details - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Customer Cheque Detail') }} :: ({{$chequedetail->id}}) </h3></div>
			<div class="panel-body">
				{!! Form::model($chequedetail, array('method' => 'PATCH', 'route' => array('cheques.chequedetails.update', $cheque->id, $chequedetail->id))) !!}

					@include('cheque_details._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection