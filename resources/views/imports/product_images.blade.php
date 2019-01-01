@extends('layouts.master')

@section('title') {{ l('Import - Products') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

        <a href="{{ route('products.images.export') }}" class="btn xbtn-sm btn-grey" style="margin-right: 21px" 
                title="{{l('Export Headers')}}"><i class="fa fa-file-excel-o"></i> {{l('Export Headers')}}</a>

                <a href="{{ URL::to('products') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Products') }}</a>
            </div>
            <h2><a href="{{ URL::to('products') }}">{{ l('Products') }}</a> <span style="color: #cccccc;">/</span> {{ l('Images') }} <span style="color: #cccccc;">/</span> {{ l('Import') }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_main_data" href="{{ route('products.import') }}" class="list-group-item">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Products') }}
            </a>
            <a id="b_purchases" href="{{ route('products.images.import') }}" class="list-group-item active">
               <i class="fa fa-picture-o"></i>
               &nbsp; {{ l('Images') }}
            </a>
            <!-- a id="b_sales" href="#sales" class="list-group-item">
               <i class="fa fa-share-square-o"></i>
               &nbsp; {{ l('Sales') }}
            </a>
            <a id="b_inventory" href="#inventory" class="list-group-item">
               <i class="fa fa-th"></i>
               &nbsp; {{ l('Stocks') }}
            </a>
            <a id="b_manufacturing" href="#manufacturing" class="list-group-item">
               <i class="fa fa-cubes"></i>
               &nbsp; {{ l('Manufacturing') }}
            </a>

            <a id="b_images" href="#images" class="list-group-item">
               <i class="fa fa-picture-o"></i>
               &nbsp; {{ l('Images') }}
            </a>
            <a id="b_internet" href="#internet" class="list-group-item">
               <i class="fa fa-cloud"></i>
               &nbsp; {{ l('Internet') }}
            </a>
            <a id="b_" href="#" class="list-group-item">
               <i class="fa fa-cloud"></i>
               &nbsp; 
            </a -->
         </div>

      </div>
      
      <div class="col-lg-8 col-md-8 col-sm-8">

          @include('imports._panel_product_images')

      </div>

   </div>
</div>
@endsection

@section('scripts') 
<script type="text/javascript">

   $(document).ready(function() {
      //
   });

</script>

@endsection


@section('styles')

@endsection