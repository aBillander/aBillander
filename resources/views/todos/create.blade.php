@extends('layouts.master')

@section('title') {{ l('Todos - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Todo') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'todos.store')) !!}

					@include('todos._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts') @parent

<script>

  $(function() {

  	$('#due_date').val('{{ abi_date_form_short( 'now' ) }}');

  });
  
</script>

@endsection
