@extends('layouts.master')

@section('title') {{ l('Sales Representatives - Edit') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ URL::to('salesreps') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Sales Representatives') }}</a>
            </div>
            <h2><a href="{{ URL::to('salesreps') }}">{{ l('Sales Representatives') }}</a> <span style="color: #cccccc;">/</span> {{ $salesrep->alias }}</h2>
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_main" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
            <a id="b_salesrepuser" href="#salesrepuser" class="list-group-item">
               <i class="fa fa-bolt"></i>
               &nbsp; {{ l('ABSRC Access') }}
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            <div class="panel panel-info" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>
                {!! Form::model($salesrep, array('method' => 'PATCH', 'route' => array('salesreps.update', $salesrep->id))) !!}

                    @include('sales_reps._form_edit')

                {!! Form::close() !!}
            </div>

@if (\App\Configuration::isTrue('ENABLE_SALESREP_CENTER') )

          @include('sales_reps._panel_salesrepuser')

@endif
      </div>
   </div>
</div>
@endsection

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
      $("#panel_products").hide();
      $("#panel_pricerules").hide();
 //     $("#panel_statistics").hide();
      $("#panel_salesrepuser").hide();

      $("#b_main").removeClass('active');
      $("#b_commercial").removeClass('active');
 //     $("#b_bankaccounts").removeClass('active');
      $("#b_addressbook").removeClass('active');
 //     $("#b_specialprices").removeClass('active');
 //     $("#b_accounting").removeClass('active');
      $("#b_orders").removeClass('active');
      $("#b_products").removeClass('active');
      $("#b_pricerules").removeClass('active');
//      $("#b_statistics").removeClass('active');
      $("#b_salesrepuser").removeClass('active');
      
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
      else if(window.location.hash.substring(1) == 'statistics')
      {
         $("#panel_statistics").show();
         $("#b_statistics").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'salesrepuser')
      {
         $("#panel_salesrepuser").show();
         $("#b_salesrepuser").addClass('active');
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