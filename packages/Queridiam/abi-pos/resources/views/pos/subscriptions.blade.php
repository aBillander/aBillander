@extends('layouts.app')
@section('title', __( 'lang_v1.subscriptions'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang( 'lang_v1.subscriptions') @show_tooltip(__('lang_v1.recurring_invoice_help'))</h1>
</section>

<!-- Main content -->
<section class="content no-print">
	<div class="box">
        <div class="box-header">
        	<!-- <h3 class="box-title"></h3> -->
        </div>
        <div class="box-body">
            @can('sell.view')
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('subscriptions_filter_date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('subscriptions_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
                        </div>
                    </div>
                </div>
                @include('sale_pos.partials.subscriptions_table')
            @endcan
        </div>
    </div>
</section>
@stop

@section('javascript')
@include('sale_pos.partials.subscriptions_table_javascript')
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection