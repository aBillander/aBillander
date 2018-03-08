@extends('layouts.master')

@section('title') {{ l('Production Sheets - Show') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

        <a href="{{ URL::to('productionsheets/'.$sheet->id.'/calculate') }}" class="btn btn-success"><i class="fa fa-cog"></i> {{ l('Update Sheet') }}</a>

        <a href="{{ URL::to('productionsheets') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Production Sheets') }}</a>
    </div>
    <h2>
        <a href="{{ URL::to('productionsheets') }}">{{ l('Production Sheets') }}</a> <span style="color: #cccccc;">::</span> {{ abi_date_form_short($sheet->due_date) }}
        @if ($sheet->is_dirty AND 0)
              <button type="button" class="btn btn-sm btn-danger" title="{{l('Need Update')}}">
                  <i class="fa fa-hand-stop-o"></i>
              </button>
        @endif
    </h2>        
</div>

    </div>
</div> 


<div class="container-fluid">

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-info" id="panel_customer_orders">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-user"></i> &nbsp; {{ l('Customer Orders') }}</h3>
               </div>
                    @include('production_sheets._panel_customer_orders')
            </div>
      </div>
   </div>
 
@if ($sheet->productsNotScheduled()->count())     

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-danger" id="panel_not_scheduled_products">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-hand-stop-o"></i> &nbsp; {{ l('Finished Product Requirements not in Production') }}</h3>
               </div>
                    @include('production_sheets._panel_not_scheduled_products')
            </div>
      </div>
   </div>
   
@endif

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-success" id="panel_production_orders">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-cubes"></i> &nbsp; {{ l('Production Orders') }}</h3>
               </div>
                    @include('production_sheets._panel_production_orders')
            </div>
      </div>
   </div>

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-warning" id="panel_material_requirements">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-th-list"></i> &nbsp; {{ l('Material Requirements') }}</h3>
               </div>
                    @include('production_sheets._panel_material_requirements')
            </div>
      </div>
   </div>

</div>


@include('production_sheets._modal_production_order_form')


@endsection


@section('scripts') @parent 

<!-- script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script -->

@endsection
