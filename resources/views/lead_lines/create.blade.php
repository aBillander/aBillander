@extends('layouts.master')

@section('title') {{ l('Lead Lines - Create') }} :: @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>{{ $lead->name }}</strong> :: {{ l('New Lead Line') }}</h3>
				<h3 class="panel-title">{{ $lead->party->name_commercial }}</h3>
			</div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('leads.leadlines.store', $lead->id))) !!}

					@include('lead_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop