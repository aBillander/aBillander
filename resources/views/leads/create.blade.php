@extends('layouts.master')

@section('title') {{ l('Leads - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Lead') }}
				@if ($party != null)
				 :: <strong>{{ $party->name_commercial }}</strong>
				@endif

			</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'leads.store')) !!}

					@include('leads._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection