@extends('abcc.layouts.master')

@section('title') {{ l('Customer Price Rules') }} @parent @stop


@section('content')

<div class="page-header">
    <h2>
        {{ l('Customer Price Rules') }}
    </h2>
</div>


@include('abcc.customer._block_pricerules_price')

@include('abcc.customer._block_pricerules_promo')

@include('abcc.customer._block_pricerules_pack')


@include('abcc.catalogue._modal_view_product')


@endsection


{{-- *************************************** --}}