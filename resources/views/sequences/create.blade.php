@extends('layouts.master')

@section('title') {{ l('Sequences - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('New Sequence') }} :: {{l('Format')}} <span id="sequence_format" class="label label-info"></span></h3></div>
			<div class="panel-body">

				{{-- @include('errors.list') --}}

				{!! Form::open(array('route' => 'sequences.store')) !!}

					@include('sequences._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')    @parent

    <script type="text/javascript">

        $(document).ready(function() {

        	$('#length').val({{ old( 'length', 4 )}});
        	$('#next_id').val({{ old( 'next_id', 1 )}});

        	$('#prefix').val({{ old( 'prefix', '' )}});
        	$('#separator').val({{ old( 'separator', '' )}});

        	show_sequence_format();

        });

    </script> 

@endsection