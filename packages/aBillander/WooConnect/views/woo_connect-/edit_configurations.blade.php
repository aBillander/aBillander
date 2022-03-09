@extends('layouts.master')

@section('title') {{ l('WooCommerce Connect - Configuration') }} @parent @stop


@section('content')

{{-- abi_r($wooconfs) --}}

<div class="row">
	<div class="col-md-5 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('WooCommerce Connect - Configurations') }}</h3></div>
			<div class="panel-body">

				{{-- @include('errors.list') --}}

				<p>{{ l('Retrieve your WooCommerce shop Settings.') }}</p>

				{!! Form::open(array('route' => 'wooconnect.configuration.update', 'class' => 'form' )) !!}

<div id="wooconfs" style="display:none;">
@foreach ( $wooconfs as $wooconf )
<div class="row">

	<div class="form-group col-lg-6 col-md-6 col-sm-6">

	    <div class="text-right">
	    	<label>{{ $wooconf['id'] }}</label>
	    </div>

	{{-- abi_r($wooconf) --}}

	    {{-- !! Form::label($tax['slug'], $tax['name']) !! --}}
	    <!-- div class="text-right"><label>{ { $tax['name'].' ['.$tax['slug'].']' } }</label></div -->
	    {{-- !! Form::text($tax['slug'], null, array('class' => 'form-control')) !! --}}
	</div>
	<div class="form-group col-lg-6 col-md-6 col-sm-6 { { $errors->has('dic.'.$dic[$tax['slug']]) ? 'has-error' : '' } }">

		{{ $wooconf['value'] }}

        {{-- !! Form::select('dic['.$dic[$tax['slug']].']', array('0' => l('-- Please, select --', [], 'layouts')) + $taxList, $dic_val[$tax['slug']], array('class' => 'form-control')) !! --}}
    	{{-- !! $errors->first('dic.'.$dic[$tax['slug']], '<span class="help-block">:message</span>') !! --}}
    </div>

</div>

@endforeach
</div>


{!! Form::submit(l('Update', [], 'layouts'), array('class' => 'btn btn-success', 'onclick' => 'this.disabled=true;this.form.submit();')) !!}
{!! link_to_route('worders.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection