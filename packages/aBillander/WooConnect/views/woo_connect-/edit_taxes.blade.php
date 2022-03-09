@extends('layouts.master')

@section('title') {{ l('WooCommerce Connect - Configuration') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-5 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('WooCommerce Connect - Taxes Dictionary') }}</h3></div>
			<div class="panel-body">

				{{-- @include('errors.list') --}}

				{!! Form::open(array('route' => 'wooconnect.configuration.taxes.update', 'class' => 'form' )) !!}

@foreach ( $wootaxes as $tax )
<div class="row">

	<div class="form-group col-lg-6 col-md-6 col-sm-6">
	    {{-- !! Form::label($tax['slug'], $tax['name']) !! --}}
	    <div class="text-right" title="{{' ['.$tax['slug'].'] '}}"><label>{{ $tax['name'] }}</label></div>
	    {{-- !! Form::text($tax['slug'], null, array('class' => 'form-control')) !! --}}
	</div>
	<div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('dic.'.$dic[$tax['slug']]) ? 'has-error' : '' }}">
        {!! Form::select('dic['.$dic[$tax['slug']].']', array('' => l('-- Please, select --', [], 'layouts')) + $taxList, $dic_val[$tax['slug']], array('class' => 'form-control')) !!}
    	{!! $errors->first('dic.'.$dic[$tax['slug']], '<span class="help-block">:message</span>') !!}
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