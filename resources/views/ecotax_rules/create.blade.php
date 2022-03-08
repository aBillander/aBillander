@extends('layouts.master')

@section('title') {{ l('Ecotax Rules - Create') }} :: @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $ecotax->name }}</strong> :: {{ l('New Ecotax Rule') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('ecotaxes.ecotaxrules.store', $ecotax->id))) !!}

					@include('ecotax_rules._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection