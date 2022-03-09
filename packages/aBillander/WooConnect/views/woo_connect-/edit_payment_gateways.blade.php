@extends('layouts.master')

@section('title') {{ l('WooCommerce Connect - Configuration') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-5 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('WooCommerce Connect - Payment Gateways Dictionary') }}</h3></div>
			<div class="panel-body">

				{{-- @include('errors.list') --}}

				{!! Form::open(array('route' => 'wooconnect.configuration.paymentgateways.update', 'class' => 'form' )) !!}

@foreach ( $woopgates as $pgates )
<div class="row">

	<div class="form-group col-lg-6 col-md-6 col-sm-6">
	    {{-- !! Form::label($pgates['id'], $pgates['method_title']) !! --}}
	    <div class="text-right">
	    <label>
	    @if (!$pgates['enabled'])
	    	<i class="fa fa-warning xalert alert-warning xalert-block" title="{{ l('Disabled') }}"></i> 
	    @endif
	    {{ ' ['.$pgates['id'].'] '.$pgates['method_title'] }}</label><br />{{ $pgates['title'] }}</div>
	    {{-- !! Form::text($pgates['id'], null, array('class' => 'form-control')) !! --}}
	</div>
	<div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('dic.'.$dic[$pgates['id']]) ? 'has-error' : '' }}">
        {!! Form::select('dic['.$dic[$pgates['id']].']', array('' => l('-- Please, select --', [], 'layouts')) + $pgatesList, $dic_val[$pgates['id']], array('class' => 'form-control')) !!}
    	{!! $errors->first('dic.'.$dic[$pgates['id']], '<span class="help-block">:message</span>') !!}
    </div>

</div>

@endforeach

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('worders.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection