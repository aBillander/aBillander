@extends('pos::layouts.app')

@section('title') {{ l('Point of Sale') }} @parent @endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>{{ l('Point of Sale') }}
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('pos::components.filters', ['title' => __('report.filters')])
        @include('pos::sell.partials.sell_list_filters')
    @endcomponent

    @component('pos::components.widget', ['class' => 'box-primary', 'title' => __( 'sale.list_pos')])
        @can('sell.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" href="{{action('SellPosController@create')}}">
                    <i class="fa fa-plus"></i> @lang('messages.add')</a>
                </div>
            @endslot
        @endcan
        @can('sell.view')
            <input type="hidden" name="is_direct_sale" id="is_direct_sale" value="0">
            @include('sale_pos.partials.sales_table')
        @endcan
    @endcomponent
</section>
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<!-- This will be printed -->
<!-- <section class="invoice print_section" id="receipt_section">
</section> -->


@stop

@section('javascript')
@include('pos::pos.partials.sale_table_javascript')
<script src="{{ asset('assets/abi-pos/js/payment.js?v=' . $asset_v) }}"></script>
@endsection