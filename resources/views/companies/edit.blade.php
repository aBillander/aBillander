@extends('layouts.master')

@section('title') {{ l('Companies - Edit') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- a href="{{ URL::to('companies') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Companies') }}</a -->
            </div>
            <h2><a href="{{ URL::to('#') }}">{{ l('Companies') }}</a> <span style="color: #cccccc;">/</span> {{ $company->name_fiscal }}</h2>
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
            <a id="b_bankaccounts" href="#bankaccounts" class="list-group-item">
               <i class="fa fa-briefcase"></i>
               &nbsp; {{ l('Bank Accounts') }}
            </a>
         </div>

         <div class="list-group" style="background-color: #d34615;"><?php $img = AbiContext::getContext()->company->company_logo ?>
@if ( $img )
            <img src="{{ URL::to( AbiCompany::imagesPath() . $img ) }}" class="img-responsive center-block" style="border: 1px solid #dddddd;">
@else
            <img src="{{ URL::to( AbiCompany::imagesPath() . 'default-medium_default.png' ) . '?'. 'time='. time() }}" class="img-responsive center-block" style="border: 1px solid #dddddd;">
            
@endif
         </div>

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
         {!! Form::model($company, array('method' => 'PATCH', 'route' => array('companies.update', $company->id), 'files' => true)) !!}
            <div class="panel panel-info" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }} ({{$company->id}})</h3>
               </div>
               
              @include('companies._form')
              
            </div>
          {!! Form::close() !!}

          @include('companies._panel_bankaccounts')

      </div>
   </div>
</div>
@endsection


@section('scripts') @parent 

<script type="text/javascript">
$(function() {

  // See: https://www.abeautifulsite.net/whipping-file-inputs-into-shape-with-bootstrap-3
  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }

      });

      route_url();
      window.onpopstate = function(){
         route_url();
      }
  });
   


   function route_url()
   {
      $("#panel_main").hide();
      $("#panel_commercial").hide();
      $("#panel_bankaccounts").hide();
      $("#panel_addressbook").hide();
 //     $("#panel_specialprices").hide();
 //     $("#panel_accounting").hide();
      $("#panel_orders").hide();
      $("#panel_products").hide();
      $("#panel_pricerules").hide();
 //     $("#panel_statistics").hide();
      $("#panel_customeruser").hide();

      $("#b_main").removeClass('active');
      $("#b_commercial").removeClass('active');
      $("#b_bankaccounts").removeClass('active');
      $("#b_addressbook").removeClass('active');
 //     $("#b_specialprices").removeClass('active');
 //     $("#b_accounting").removeClass('active');
      $("#b_orders").removeClass('active');
      $("#b_products").removeClass('active');
      $("#b_pricerules").removeClass('active');
//      $("#b_statistics").removeClass('active');
      $("#b_customeruser").removeClass('active');
      
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
      else if(window.location.hash.substring(1) == 'statistics')
      {
         $("#panel_statistics").show();
         $("#b_statistics").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'customeruser')
      {
         $("#panel_customeruser").show();
         $("#b_customeruser").addClass('active');
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
  
});
</script>

@endsection