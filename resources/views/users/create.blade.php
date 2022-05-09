@extends('layouts.master')

@section('title') {{ l('Users - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New User') }}</h3></div>
			<div class="panel-body">

        @include('errors.list')

				{!! Form::open(array('route' => 'users.store')) !!}

          @include('users._form')

        {!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')    @parent

<script type="text/javascript">
   
   $(document).ready(function() {

        // Set default language
        $('select[name="language_id"]').val( {{ intval(AbiConfiguration::get('DEF_LANGUAGE')) }} );

        // Set focus
        $("#name").focus();

   });

</script>


@endsection