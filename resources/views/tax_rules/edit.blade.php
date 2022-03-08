@extends('layouts.master')

@section('title') {{ l('Tax Rules - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Tax Rule') }} :: ({{$taxrule->id}}) {{$taxrule->name}}</h3></div>
			<div class="panel-body">
				{!! Form::model($taxrule, array('method' => 'PATCH', 'route' => array('taxes.taxrules.update', $tax->id, $taxrule->id))) !!}

					@include('tax_rules._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection