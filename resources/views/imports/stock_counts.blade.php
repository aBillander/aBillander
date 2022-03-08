@extends('layouts.master')

@section('title') {{ l('Import - Stock Counts') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ URL::to('stockcounts') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Stock Counts') }}</a>
            </div>
            <h2><a href="{{ URL::to('stockcounts') }}">{{ l('Stock Counts') }}</a> <span style="color: #cccccc;">/</span> {{ l('Import') }} <span style="color: #cccccc;">::</span> {{$stockcount->name}} </h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-3">

          <div class="panel panel-default">
          <div class="panel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <br />

                    <span class="badge" style="background-color: #3a87ad;"> {{ $stockcount->warehouse->alias }} </span> {{ $stockcount->warehouse->name }}
                        <br />
                        <br />
                    @if ($stockcount->initial_inventory)
                      <span class="label label-success">{{ l('Initial Inventory') }}</span>
                    @else
                      <span class="label label-success">{{ l('Stock Adjustment') }}</span>
                    @endif
{{--
                    @if ( $stockcount->price_is_tax_inc )
                        <br />
                        <span class="label label-info">{{ l('Tax Included', [], 'stockcounts') }}</span>
                    @endif
--}}
            <br />
            <br />

          </div>
          </div>

         <div class="list-group">
            <!-- a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
            <a id="b_purchases" href="#purchases" class="list-group-item">
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

          @include('imports._panel_stock_counts')

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