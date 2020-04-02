@extends('abcc.layouts.master')

@section('title') {{ l('Shopping Cart') }} @parent @stop


@section('content') 

@if ( 1 || $config['display_with_taxes'] )
@else
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- Button trigger modal -->
                <!-- button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_address" title=" Nueva Dirección Postal ">
                  <i class="fa fa-plus"></i> Dirección
                </button -->
                <!-- a href="{{ URL::to('invoices/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Documento</a --> 
                <!-- div class="btn-group">
                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Document', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('customerorders.create.withcustomer', 1) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                    </ul>
                </div>
                <a href="{{ URL::to('customers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customers') }}</a -->
            </div>
            <h2><!-- a href="{{ URL::to('customers') }}">{{ l('Shopping Cart') }}</a> <span style="color: #cccccc;">/</span --> {{  l('Shopping Cart') }} [{{ $cart->id }}] &nbsp;
            </h2>
        </div>
    </div>
</div>
@endif

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-5 col-md-5 col-sm-5">

          @include('abcc.cart._panel_product_large')

      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->

      <div class="col-lg-2 col-md-2 col-sm-2">

          @include('abcc.cart._panel_shipping_address_header')

      </div>

      <div class="col-lg-5 col-md-5 col-sm-5">

          @include('abcc.cart._panel_confirm_header')

      </div>

   </div>

   <div class="row">
      
      <div class="col-lg-1 col-md-1 col-sm-1">

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">

          @include('abcc.cart._panel_cart')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->
      
      <div class="col-lg-1 col-md-1 col-sm-1">

      </div>

   </div>
{{--
   <div class="row">

      <div class="col-lg-4 col-md-4 col-sm-4">

          

      </div>

      <div class="col-lg-4 col-md-4 col-sm-4">

          @include('abcc.cart._panel_shipping_address')

      </div>

      <div class="col-lg-4 col-md-4 col-sm-4">

          @include('abcc.cart._panel_confirm')

      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->

   </div>
--}}
</div>

@include('layouts/back_to_top_button')

@endsection



@include('abcc.cart._modal_pricerules')



@section('styles')    @parent

<style>
  /* 
  http://twitterbootstrap3buttons.w3masters.nl/?color=%232BA9E1
  https://bootsnipp.com/snippets/M3x9

  */
.btn-custom {
  color: #fff;
  background-color: #ff0084;
  border-color: #ff0084;
}
.btn-custom:hover,
.btn-custom:focus,
.btn-custom:active,
.btn-custom.active {
  background-color: #e60077;
  border-color: #cc006a;
}
.btn-custom.disabled:hover,
.btn-custom.disabled:focus,
.btn-custom.disabled:active,
.btn-custom.disabled.active,
.btn-custom[disabled]:hover,
.btn-custom[disabled]:focus,
.btn-custom[disabled]:active,
.btn-custom[disabled].active,
fieldset[disabled] .btn-custom:hover,
fieldset[disabled] .btn-custom:focus,
fieldset[disabled] .btn-custom:active,
fieldset[disabled] .btn-custom.active {
  background-color: #ff0084;
  border-color: #ff0084;
}


/* Cart Stuff */
/* #div_cart_lines .show-pricerules {margin-left:1em} */
#div_cart_lines td {vertical-align: middle}
.crossed {text-decoration: line-through;}


</style>

@endsection




{{--
@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {

   });

</script>
@endsection
--}}