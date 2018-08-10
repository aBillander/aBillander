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
                <!-- a href="{{ URL::to('invoices/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Documento</a --> 
                <div class="btn-group">
                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Document', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('customer.createorder', $customer->id) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>
                <a href="{{ URL::to('customers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customers') }}</a>
            </div>
            <h2><a href="{{ URL::to('customers') }}">{{ l('Customers') }}</a> <span style="color: #cccccc;">/</span> {{ $customer->name_fiscal }}</h2>
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
               <i class="fa fa-dashboard"></i></span>
               &nbsp; {{ l('Commercial') }}
            </a>
            <!-- a id="b_bankaccounts" href="#bankaccounts" class="list-group-item">
               <i class="fa fa-briefcase"></i>
               &nbsp; Bancos
            </a -->
            <a id="b_addressbook" href="#addressbook" class="list-group-item">
               <i class="fa fa-address-book"></i></span>
               &nbsp; {{ l('Address Book') }}
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
            <!-- a id="b_statistics" href="#statistics" class="list-group-item">
               <i class="fa fa-bar-chart"></i>
               &nbsp; {{ l('Statistics') }}
            </a -->
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

         {!! Form::model($customer, array('route' => array('customers.update', $customer->id), 'method' => 'PUT', 'class' => 'form')) !!}
            
          @include('customers._panel_main_data')

          @include('customers._panel_commercial')

         {!! Form::close() !!}

          @include('customers._panel_addressbook')

          @include('customers._panel_orders')
{{--
          @include('customers._panel_statistics')
--}}
      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>
@stop

@section('scripts')     @parent
<script type="text/javascript">
   function route_url()
   {
      $("#panel_main").hide();
      $("#panel_commercial").hide();
 //     $("#panel_bankaccounts").hide();
      $("#panel_addressbook").hide();
 //     $("#panel_specialprices").hide();
 //     $("#panel_accounting").hide();
      $("#panel_orders").hide();
 //     $("#panel_statistics").hide();

      $("#b_main").removeClass('active');
      $("#b_commercial").removeClass('active');
 //     $("#b_bankaccounts").removeClass('active');
      $("#b_addressbook").removeClass('active');
 //     $("#b_specialprices").removeClass('active');
 //     $("#b_accounting").removeClass('active');
      $("#b_orders").removeClass('active');
//      $("#b_statistics").removeClass('active');
      
      if(window.location.hash.substring(1) == 'commercial')
      {
         $("#panel_commercial").show();
         $("#b_commercial").addClass('active');
         // document.f_cliente.codgrupo.focus();
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
      else if(window.location.hash.substring(1) == 'statistics')
      {
         $("#panel_statistics").show();
         $("#b_statistics").addClass('active');
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