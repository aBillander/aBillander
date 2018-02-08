@extends('layouts.master')

@section('title') {{ l('Users - Create') }} @parent @stop


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

@stop


@section('scripts')    @parent

    <script type="text/javascript">

        // Set default language
        $('select[name="language_id"]').val( {{ intval(\App\Configuration::get('DEF_LANGUAGE')) }} );

    </script>


@endsection