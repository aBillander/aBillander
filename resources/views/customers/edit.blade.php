@extends('layouts.master')

@section('title') {{ l('Customers - Edit') }} @parent @endsection


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
@else
                <a href="{{ route('customer.shippingslipable.orders', [$customer->id]) }}" class="btn btn-navy" style="margin-right: 72px;"><i class="fa fa-object-group"></i> {{l('Group Orders')}}</a>

                <a href="{{ route('customer.invoiceable.shippingslips', [$customer->id]) }}" class="btn btn-info" style="margin-right: 72px;"><i class="fa fa-money"></i> &nbsp;{{l('Invoice Shipping Slips')}}</a>

                <div class="btn-group">
                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Document', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('customerquotations.create.withcustomer', $customer->id) }}">{{l('Quotation', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customerorders.create.withcustomer', $customer->id) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customershippingslips.create.withcustomer', $customer->id) }}">{{l('Shipping Slip', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customerinvoices.create.withcustomer', $customer->id) }}">{{l('Invoice', [], 'layouts')}}</a></li>
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>
@endif

                <div class="btn-group" style="margin-right: 36px;">
                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Go to', [], 'layouts')}}" style="background-color: #31b0d5;
border-color: #269abc;"><i class="fa fa-mail-forward"></i> &nbsp;{{l('Go to', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu pull-right">
                      <li><a href="{{ route('customer.quotations', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Quotations', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customer.orders', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Orders', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customer.shippingslips', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Shipping Slips', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customer.invoices', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Invoices', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customer.vouchers', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Customer Vouchers', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ URL::to('customers') }}">{{ l('Back to Customers') }}</a></li>
                    </ul>
                </div>

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
            <a id="b_contacts" href="#contacts" class="list-group-item" xstyle="background-color: #fcf8e3; color: #c09853;">
               <i class="fa fa-users"></i>
               &nbsp;{{ l('Contacts') }}
            </a>
            <a id="b_actions" href="#actions" class="list-group-item" xstyle="background-color: #fcf8e3; color: #c09853;">
               <i class="fa fa-tasks"></i>
               &nbsp;{{ l('Commercial Actions') }}
            </a>
            <!-- a id="b_specialprices" href="#specialprices" class="list-group-item">
               <i class="fa fa-list-alt"></i>
               &nbsp; Precios Especiales
            </a -->
            <!-- a id="b_accounting" href="#accounting" class="list-group-item">
               <i class="fa fa-book"></i></span>
               &nbsp; Contabilidad
            </a -->
            <a id="b_orders" href="#orders" class="list-group-item">
               <i class="fa fa-file-text-o"></i>
               &nbsp; {{ l('Orders') }}
            </a>
            <a id="b_products" href="#products" class="list-group-item">
               <i class="fa fa-th"></i>
               &nbsp; {{ l('Products') }}
            </a>
            <a id="b_pricerules" href="#pricerules" class="list-group-item">
               <i class="fa fa-gavel"></i>
               &nbsp; {{ l('Price Rules') }}
            </a>
            <a id="b_attachments" href="#attachments" class="list-group-item">
               <i class="fa fa-paperclip"></i>
               &nbsp; {{ l('Attachments', 'layouts') }}
            </a>
            <!-- a id="b_statistics" href="#statistics" class="list-group-item">
               <i class="fa fa-bar-chart"></i>
               &nbsp; {{ l('Statistics') }}
            </a -->

@if (AbiConfiguration::isTrue('ENABLE_CUSTOMER_CENTER') )

            <a id="b_customerusers" href="#customerusers" class="list-group-item">
               <i class="fa fa-bolt"></i>
               &nbsp; {{ l('ABCC Access') }}
            </a>

@endif

@if ( AbiConfiguration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )

            <a id="b_webshop" href="#webshop" class="list-group-item">
               <!-- i class="fa fa-cloud"></i -->
               <i class="fa fa-wordpress text-info"></i>
               &nbsp; {{ l('Web Shop') }}
            </a>

@endif
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

         {!! Form::model($customer, array('route' => array('customers.update', $customer->id), 'method' => 'PUT', 'class' => 'form')) !!}
            
          @include('customers._panel_main_data')

          @include('customers._panel_commercial')

         {!! Form::close() !!}

          @include('customers._panel_bankaccounts')

          @include('customers._panel_addressbook')

          @include('customers._panel_contacts')

          @include('customers._panel_actions')

          @include('customers._panel_orders')

          @include('customers._panel_products')

          @include('customers._panel_pricerules')

          @include('customers._panel_attachments')
{{--
          @include('customers._panel_statistics')
--}}

@if (AbiConfiguration::isTrue('ENABLE_CUSTOMER_CENTER') )

          @include('customers._panel_customer_users')

@endif


@if ( AbiConfiguration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )

          @include('customers._panel_webshop')

@endif

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
      $("#panel_contacts").hide();
      $("#panel_actions").hide();
 //     $("#panel_specialprices").hide();
 //     $("#panel_accounting").hide();
      $("#panel_orders").hide();
      $("#panel_products").hide();
      $("#panel_pricerules").hide();
      $("#panel_attachments").hide();
 //     $("#panel_statistics").hide();
      $("#panel_customerusers").hide();
      $("#panel_webshop").hide();

      $("#b_main").removeClass('active');
      $("#b_commercial").removeClass('active');
      $("#b_bankaccounts").removeClass('active');
      $("#b_addressbook").removeClass('active');
      $("#b_contacts").removeClass('active');
      $("#b_actions").removeClass('active');
 //     $("#b_specialprices").removeClass('active');
 //     $("#b_accounting").removeClass('active');
      $("#b_orders").removeClass('active');
      $("#b_products").removeClass('active');
      $("#b_pricerules").removeClass('active');
      $("#b_attachments").removeClass('active');
//      $("#b_statistics").removeClass('active');
      $("#b_customerusers").removeClass('active');
      $("#b_webshop").removeClass('active');
      
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
      else if(window.location.hash.substring(1) == 'contacts')
      {
         $("#panel_contacts").show();
         $("#b_contacts").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'actions')
      {
         $("#panel_actions").show();
         $("#b_actions").addClass('active');
         getCustomerActions();
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
      else if(window.location.hash.substring(1) == 'attachments')
      {
         $("#panel_attachments").show();
         $("#b_attachments").addClass('active');
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
      else if(window.location.hash.substring(1) == 'webshop')
      {
         $("#panel_iwebshop").show();
         $("#customer-webshop-data").show();
         $("#b_webshop").addClass('active');
         if ($("#customer-webshop-data-content").html() == '')
          getCustomerWebShopEmbedData();
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


@section('styles')    @parent

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>

@endsection
