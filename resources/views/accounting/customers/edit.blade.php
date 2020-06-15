@extends('layouts.master')

@section('title') {{ l('Customers - Edit') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- Button trigger modal -->
                <!-- button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_address" title=" Nueva Dirección Postal ">
                  <i class="fa fa-plus"></i> Dirección
                </button -->

@if ( $customer->blocked )
                <span class="alert alert-danger" style="margin-right: 72px;">{{l('This Customer is BLOCKED')}}</span>
@endif

            </div>
            <h2><a href="{{ URL::to('customers') }}">{{ l('Customers') }}</a> <span style="color: #cccccc;">/</span> {{ $customer->name_regular }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_main" href="#" class="list-group-item active">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
            <a id="b_commercial" href="#commercial" class="list-group-item">
               <i class="fa fa-dashboard"></i>
               &nbsp; {{ l('Commercial') }}
            </a>
            <a id="b_bankaccounts" href="#bankaccounts" class="list-group-item">
               <i class="fa fa-briefcase"></i>
               &nbsp; {{ l('Bank Accounts') }}
            </a>
            <a id="b_addressbook" href="#addressbook" class="list-group-item">
               <i class="fa fa-address-book"></i>
               &nbsp; {{ l('Address Book') }}
            </a>

            <a id="b_accounting" href="#accounting" class="list-group-item">
               <i class="fa fa-book"></i></span>
               &nbsp; Contabilidad
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

         {!! Form::model($customer, array('route' => array('accounting.customers.update', $customer->id), 'method' => 'PUT', 'class' => 'form')) !!}
            
          @include('accounting.customers._panel_main_data')

          @include('accounting.customers._panel_commercial')

          @include('accounting.customers._panel_accounting')

         {!! Form::close() !!}

          @include('accounting.customers._panel_bankaccounts')

          @include('accounting.customers._panel_addressbook')
{{--
          @include('accounting.customers._panel_statistics')
--}}

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>
@endsection

@section('scripts')     @parent
<script type="text/javascript">
   function route_url()
   {
      $("#panel_main").hide();
      $("#panel_commercial").hide();
      $("#panel_bankaccounts").hide();
      $("#panel_addressbook").hide();
 //     $("#panel_specialprices").hide();
      $("#panel_accounting").hide();
      $("#panel_orders").hide();
      $("#panel_products").hide();
      $("#panel_pricerules").hide();
 //     $("#panel_statistics").hide();
      $("#panel_customerusers").hide();

      $("#b_main").removeClass('active');
      $("#b_commercial").removeClass('active');
      $("#b_bankaccounts").removeClass('active');
      $("#b_addressbook").removeClass('active');
 //     $("#b_specialprices").removeClass('active');
      $("#b_accounting").removeClass('active');
      $("#b_orders").removeClass('active');
      $("#b_products").removeClass('active');
      $("#b_pricerules").removeClass('active');
//      $("#b_statistics").removeClass('active');
      $("#b_customerusers").removeClass('active');
      
      if(window.location.hash.substring(1) == 'commercial')
      {
         $("#panel_commercial").show();
         $("#b_commercial").addClass('active');
         // document.f_cliente.codgrupo.focus();
      }
      else if(window.location.hash.substring(1) == 'bankaccounts')
      {
         $("#panel_bankaccounts").show();
         $("#b_bankaccounts").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'addressbook')
      {
         $("#panel_addressbook").show();
         $("#b_addressbook").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'orders')
      {
         $("#panel_orders").show();
         $("#b_orders").addClass('active');
         getCustomerOrders();
      }
      else if(window.location.hash.substring(1) == 'products')
      {
         $("#panel_products").show();
         $("#b_products").addClass('active');
         getCustomerProducts();
      }
      else if(window.location.hash.substring(1) == 'pricerules')
      {
         $("#panel_pricerules").show();
         $("#b_pricerules").addClass('active');
         getCustomerPriceRules();
      }
      else if(window.location.hash.substring(1) == 'accounting')
      {
         $("#panel_accounting").show();
         $("#b_accounting").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'statistics')
      {
         $("#panel_statistics").show();
         $("#b_statistics").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'customerusers')
      {
         $("#panel_customerusers").show();
         $("#b_customerusers").addClass('active');
         getCustomerUsers();
      }
      else  
      {
         $("#panel_main").show();
         $("#b_main").addClass('active');
         // document.f_cliente.nombre.focus();
      }

      // Gracefully scrolls to the top of the page
      $("html, body").animate({ scrollTop: 0 }, "slow");
   }
   
   $(document).ready(function() {
      route_url();
      window.onpopstate = function(){
         route_url();
      }
   });

</script>
@endsection