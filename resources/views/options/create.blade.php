@extends('layouts.master')

@section('title') {{ l('Options - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $optiongroup->name }}</strong> :: {{ l('New Option') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => array('optiongroups.options.store', $optiongroup->id))) !!}
				<!-- input type="hidden" value="{{$optiongroup->id}}" name="option_group_id" -->

					@include('options._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')    @parent

    <script type="text/javascript">

        // Set default position
        $('input[name="position"]').val( 0 );

    </script>


@endsection