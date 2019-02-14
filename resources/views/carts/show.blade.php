@extends('layouts.master')

@section('title') {{ l('Customer Shopping Cart') }} @parent @stop


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
                      <li><a href="{{ route('customerorders.create.withcustomer', 1) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                    </ul>
                </div-->

                <a class="btn xbtn-sm btn-info update-cart-prices" data-html="false" data-toggle="modal" 
                        href="{{ URL::route('carts.updateprices', [$cart->id] ) }}" 
                        data-content="{{l('You are going to UPDATE all Product Prices in this Cart. Are you sure?')}}" 
                        data-title="{{ l('Carts') }} :: ({{$cart->customer->id}}) {{ $cart->customer->name }}" 
                        onClick="return false;" title="{{l('Update Cart Prices')}}"><i class="fa fa-superpowers"></i> {{l('Update Prices')}}</a>

                <a href="{{ URL::to('carts') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Shopping Carts') }}</a>
            </div>
            <h2>
              <a href="{{ URL::to('carts') }}">{{ l('Shopping Carts') }}</a> <span style="color: #cccccc;">/</span> {{ l('Shopping Cart') }} &nbsp; <span class="badge pull-right" style="background-color: #3a87ad; margin-right: 72px; margin-top: 8px;" title="{{ '' }}">{{ $cart->currency->iso_code }}</span>

                  {{l(' :: ')}} <span class="lead well well-sm">

                  <a href="{{ URL::to('customers/' . $customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{{ $customer->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$customer->identification}}<br />
                                  {{ $customer->address->address1 }} {{ $customer->address->address2 }}<br />
                                  {{ $customer->address->postcode }} {{ $customer->address->city }}, {{ $customer->address->state->name }}<br />
                                  {{ $customer->address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
                 @if($customer->sales_equalization)
                  <span id="sales_equalization_badge" class="badge" title="{{l('Equalization Tax')}}"> RE </span>
                 @endif
                 </span> 
            </h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-4 col-md-4 col-sm-4">

          @include('carts._panel_empty')
{{--
          @include('carts._panel_product')

          @include('carts._panel_confirm')
--}}
      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->
      
      <div class="col-lg-8 col-md-8 col-sm-8">

          @include('carts._panel_cart')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->

   </div>
</div>
@endsection


@include('carts/_modal_update_prices')


{{-- *************************************** --}}


{{--
@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {

   });

</script>
@endsection
--}}