@extends('abcc.layouts.master')

@section('title') {{ l('Customer Price Rules') }} @parent @endsection


@section('content')

<div class="page-header">
    <h2>
        {{ l('Customer Price Rules') }}
    </h2>
</div>



<div class="container-fluid">
   <div class="row">
      
      <div class="col-lg-1 col-md-1 col-sm-1">

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">
      	
@include('abcc.customer._block_pricerules_price')

@include('abcc.customer._block_pricerules_pack')

@include('abcc.customer._block_pricerules_promo')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->
      
      <div class="col-lg-1 col-md-1 col-sm-1">

      </div>

   </div>
</div>


@include('abcc.catalogue._modal_view_product')


@endsection


{{-- *************************************** --}}