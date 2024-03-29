@extends('layouts.master')

@section('title') {{ l('Leads - Edit') }} @parent @endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- Button trigger modal -->
                <!-- button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_address" title=" Nueva Dirección Postal ">
                  <i class="fa fa-plus"></i> Dirección
                </button -->

            </div>

            <h2><a href="{{ URL::to('leads') }}">{{ l('Leads') }}</a> <span style="color: #cccccc;">/</span> 
               <span class="lead well well-sm">

            {{ $lead->party->name_commercial }} &nbsp;
            <a href="{{ route('parties.edit', $lead->party->id) }}" class="btn btn-xs btn-warning" title="{{ l('Go to', 'layouts') }}" target="_blank"><i class="fa fa-external-link"></i></a> 

         </span> 
               <span style="color: #cccccc;">::</span> {{$lead->name}}</h2>
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
            <a id="b_leadlines" href="#leadlines" class="list-group-item">
               <i class="fa fa-magic"></i>
               &nbsp; {{ l('Lead Lines') }}
            </a>
            <a id="b_attachments" href="#attachments" class=" hidden  list-group-item">
               <i class="fa fa-paperclip"></i>
               &nbsp; {{ l('Attachments', 'layouts') }}
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            
          @include('leads._panel_main_data')

          @include('leads._panel_leadlines')
{{--

          @include('leads._panel_attachments')
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
      $("#panel_leadlines").hide();
      $("#panel_contacts").hide();
      $("#panel_addressbook").hide();
      $("#panel_attachments").hide();
 //     $("#panel_specialprices").hide();
 //     $("#panel_accounting").hide();
      $("#panel_orders").hide();
      $("#panel_products").hide();
      $("#panel_pricerules").hide();
 //     $("#panel_statistics").hide();
      $("#panel_supplierusers").hide();

      $("#b_main").removeClass('active');
      $("#b_leadlines").removeClass('active');
      $("#b_contacts").removeClass('active');
      $("#b_addressbook").removeClass('active');
      $("#b_attachments").removeClass('active');
 //     $("#b_specialprices").removeClass('active');
 //     $("#b_accounting").removeClass('active');
      $("#b_orders").removeClass('active');
      $("#b_products").removeClass('active');
      $("#b_pricerules").removeClass('active');
//      $("#b_statistics").removeClass('active');
      $("#b_supplierusers").removeClass('active');
      
      if(window.location.hash.substring(1) == 'leadlines')
      {
         $("#panel_leadlines").show();
         $("#b_leadlines").addClass('active');
         // document.f_cliente.codgrupo.focus();
      }
      else if(window.location.hash.substring(1) == 'contacts')
      {
         $("#panel_contacts").show();
         $("#b_contacts").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'addressbook')
      {
         $("#panel_addressbook").show();
         $("#b_addressbook").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'attachments')
      {
         $("#panel_attachments").show();
         $("#b_attachments").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'orders')
      {
         $("#panel_orders").show();
         $("#b_orders").addClass('active');
         // getSupplierOrders();
      }
      else if(window.location.hash.substring(1) == 'products')
      {
         $("#panel_products").show();
         $("#b_products").addClass('active');
         // getSupplierProducts();
      }
      else if(window.location.hash.substring(1) == 'pricerules')
      {
         $("#panel_pricerules").show();
         $("#b_pricerules").addClass('active');
         // getSupplierPriceRules();
      }
      else if(window.location.hash.substring(1) == 'statistics')
      {
         $("#panel_statistics").show();
         $("#b_statistics").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'supplierusers')
      {
         $("#panel_supplierusers").show();
         $("#b_supplierusers").addClass('active');
         // getSupplierUsers();
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

@include('layouts/modal_delete')
