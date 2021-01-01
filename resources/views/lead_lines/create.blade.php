@extends('layouts.master')

@section('title') {{ l('Lead Lines - Create') }} :: @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">{{ l('New Lead Line') }} 
               <span class="lead well well-sm">

            {{ $lead->party->name_commercial }} &nbsp;
            <a href="{{ route('parties.edit', $lead->party->id) }}" class="btn btn-xs btn-warning" title="{{ l('Go to', 'layouts') }}" target="_blank"><i class="fa fa-external-link"></i></a> 

         </span> :: <strong>{{ $lead->name }}</strong></h3>
			</div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('leads.leadlines.store', $lead->id))) !!}

					@include('lead_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

    			$( "#user_assigned_to_id" ).val("{{ $lead->user_assigned_to_id }}");

        });


    </script> 


@endsection
