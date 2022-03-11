@extends('layouts.master')

@section('title') {{ l('Down Payment to Suppliers - Create') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 25px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">{{ l('New Down Payment to Supplier') }}</h3>
				<h3 class="panel-title" style="margin-top: 10px">[<a class="" href="{{ URL::to('supplierorders/' .$document->id . '/edit') }}" title="{{ l('Go to', 'layouts') }}" target="_new"><strong>{{ $document->document_reference ?: l('Draft', 'layouts').' - '.$document->id }}</strong> <i class="fa fa-external-link alert-warning"></i> </a>] <span class="lead well well-sm alert-success">{{ \App\Models\Currency::viewMoneyWithSign($document->total_currency_tax_incl, $document->currency) }}</span> &nbsp; {{ $document->supplier->name_regular }}</h3>
			</div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => 'supplier.downpayments.store')) !!}

					{{ Form::hidden('supplier_id',       $document->supplier_id, ['id' => 'supplier_id']      ) }}
					{{ Form::hidden('supplier_order_id', $document->id,          ['id' => 'supplier_order_id']) }}

					@include('supplier_down_payments._form_with_document')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection