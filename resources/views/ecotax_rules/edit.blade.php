@extends('layouts.master')

@section('title') {{ l('Ecotax Rules - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Ecotax Rule') }} :: ({{$ecotaxrule->id}}) {{$ecotaxrule->name}}</h3></div>
			<div class="panel-body">
				{!! Form::model($ecotaxrule, array('method' => 'PATCH', 'route' => array('ecotaxes.ecotaxrules.update', $ecotax->id, $ecotaxrule->id))) !!}

					@include('ecotax_rules._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection