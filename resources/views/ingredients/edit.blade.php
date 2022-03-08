@extends('layouts.master')

@section('title') {{ l('Ingredients - Edit') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ URL::to('ingredients') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Ingredients') }}</a>
            </div>
            <h2><a href="{{ URL::to('ingredients') }}">{{ l('Ingredients') }}</a> <span style="color: #cccccc;">/</span> {{ $product->name }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
            <!-- a id="b_purchases" href="#purchases" class="list-group-item">
               <i class="fa fa-shopping-cart"></i>
               &nbsp; {{ l('Purchases') }}
            </a>
            <a id="b_sales" href="#sales" class="list-group-item">
               <i class="fa fa-share-square-o"></i>
               &nbsp; {{ l('Sales') }}
            </a>
            <a id="b_inventory" href="#inventory" class="list-group-item">
               <i class="fa fa-th"></i>
               &nbsp; {{ l('Stocks') }}
            </a -->
            <!-- a id="b_manufacturing" href="#manufacturing" class="list-group-item">
               <i class="fa fa-cubes"></i>
               &nbsp; {{ l('Manufacturing') }}
            </a -->

@if ( ($product->product_type == 'simple') || ($product->product_type == 'combinable') )

            <!-- a id="b_combinations" href="#combinations" class="list-group-item">
               <i class="fa fa-tags"></i>
               &nbsp; {{ l('Combinations') }}
            </a -->

@endif

            <!-- a id="b_images" href="#images" class="list-group-item">
               <i class="fa fa-picture-o"></i>
               &nbsp; {{ l('Images') }}
            </a>
            <a id="b_internet" href="#internet" class="list-group-item">
               <i class="fa fa-cloud"></i>
               &nbsp; {{ l('Internet') }}
            </a -->
         </div>

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include('ingredients._panel_main_data')
<!--

          @ include('ingredients._panel_manufacturing')

          @ include('products._panel_purchases')

          @ include('products._panel_sales')

          @ include('products._panel_inventory')

          @ include('products._panel_internet')
-->

@if ( 0 && ($product->product_type == 'simple') || ($product->product_type == 'combinable') )

          @include('products._panel_combinations')

@endif
@if ( 0 )

          @include('products._panel_images')

@endif

      </div>

   </div>
</div>
@endsection

@section('scripts') 
<script type="text/javascript">
   function route_url()
   {
      $("#panel_main_data").hide();
      $("#panel_purchases").hide();
      $("#panel_sales").hide();
      $("#panel_inventory").hide();
      $("#panel_manufacturing").hide();
      $("#panel_internet").hide();
      $("#panel_combinations").hide();
      $("#panel_images").hide();

      $("#b_main_data").removeClass('active');
      $("#b_purchases").removeClass('active');
      $("#b_sales").removeClass('active');
      $("#b_inventory").removeClass('active');
      $("#b_manufacturing").removeClass('active');
      $("#b_internet").removeClass('active');
      $("#b_combinations").removeClass('active');
      $("#b_images").removeClass('active');
      
      if(window.location.hash.substring(1) == 'purchases')
      {
         $("#panel_purchases").show();
         $("#b_purchases").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'sales')
      {
         $("#panel_sales").show();
         $("#b_sales").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'inventory')
      {
         $("#panel_inventory").show();
         $("#b_inventory").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'manufacturing')
      {
         $("#panel_manufacturing").show();
         $("#b_manufacturing").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'internet')
      {
         $("#panel_internet").show();
         $("#b_internet").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'combinations')
      {
         $("#panel_combinations").show();
         $("#b_combinations").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'images')
      {
         $("#panel_images").show();
         $("#b_images").addClass('active');
      }
      else  
      {
         $("#panel_main_data").show();
         $("#b_main_data").addClass('active');
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

{{-- Use CKEditor. See: https://artisansweb.net/install-use-ckeditor-laravel/ --}}
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'route_notes' );
</script>

<!-- script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
<script type="text/javascript">
// Select2 Plugin // https://select2.github.io
// Todo: ajax retrieve (all) groups
  $('#groups').select2({
    placeholder: "{{ l('-- Click to Select --', [], 'layouts') }}",
  });
</script -->

@endsection

@section('styles') 

<!-- link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" type="text/css" rel="stylesheet" / -->

@endsection