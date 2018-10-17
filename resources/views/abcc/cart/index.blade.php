@extends('abcc.layouts.master')

@section('title') {{ l('Customer - Shopping Cart') }} @parent @stop


@section('content') 
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
                      <li><a href="{{ route('customer.createorder', 1) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                    </ul>
                </div>
                <a href="{{ URL::to('customers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customers') }}</a -->
            </div>
            <h2><!-- a href="{{ URL::to('customers') }}">{{ l('Shopping Cart') }}</a> <span style="color: #cccccc;">/</span --> {{ l('Shopping Cart') }} &nbsp; <span class="badge pull-right" style="background-color: #3a87ad; margin-right: 72px; margin-top: 8px;" title="{{ '' }}">{{ $cart->currency->iso_code }}</span></h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-4 col-md-4 col-sm-4">

          @include('abcc.cart._panel_product')

          @include('abcc.cart._panel_confirm')

      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->
      
      <div class="col-lg-8 col-md-8 col-sm-8">

          @include('abcc.cart._panel_cart')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->

   </div>
</div>
@endsection


{{--
@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {

   });

</script>
@endsection
--}}