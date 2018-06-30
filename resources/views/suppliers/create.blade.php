@extends('layouts.master')

@section('title') {{ l('Suppliers - Create') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Supplier') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'suppliers.store')) !!}

					@include('suppliers._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')  @parent 

<script type="text/javascript">
   
   $(document).ready(function() {

      // Set some defaults:
      $('#language_id').val({{ old('language_id', \App\Configuration::get('DEF_LANGUAGE')) }});

      $('#currency_id').val({{ old('currency_id', \App\Configuration::get('DEF_CURRENCY')) }});

   });

</script>

@endsection
