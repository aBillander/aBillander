@extends('layouts.master')

@section('title') {{ l('DB Backup Job - Edit') }} :: @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit DB Backup Job') }}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'dbbackups.job.update')) !!}


<div class="row">
    <div class="form-group col-lg-12 col-md-12 col-sm-12">

        <p>{{ l('You may set a Cron Job to create a data base backup. Add this URL to your Sistem Cron:') }}</p>

        <div class="well well-lg">
		  	<a href="{{ $cronUrl }}" title="{{l('Create Data Base Backup')}}" target="_new">{{ $cronUrl }}</a>
		</div>
    </div>
</div>


	  <button class="btn btn-info" type="submit" onclick="this.form.submit();">
	     <i class="fa fa-cog"></i>
	     &nbsp; {{l('New security token')}}
	  </button>
	{!! link_to_route('dbbackups.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}


				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection