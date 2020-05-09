@extends('layouts.master')

@section('title') {{ l('Products with Low Stock') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

{!! Form::model(Request::all(), array('route' => 'products.reorder.index', 'method' => 'GET', 
"class"=>"navbar-form navbar-left", "role"=>"search", "style"=>"margin-top: 0px !important; margin-bottom: 0px !important;")) !!}
           
                      <div class="form-group">

           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                  data-content="{{ l('Use terms of three (3) characters or more', 'layouts') }}">
              <i class="fa fa-question-circle abi-help"></i>
           </a>
                        {!! Form::text('term', null, array('class' => 'form-control input-sm', "placeholder"=>l("Search terms", 'layouts'))) !!}
                      </div>

                <button class="btn btn-sm btn-default" xstyle="margin-right: 152px" type="submit" title="{{l('Search', [], 'layouts')}}">
                   <i class="fa fa-search"></i>
                   &nbsp; {{l('Search', [], 'layouts')}}
                </button>
     
{!! Form::close() !!}


        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ route('products.reorder.export', Request::all()) }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

        <a href="{{ route('helferin.home.mfg') }}" class=" hidden btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

    </div>
    <h2>
        {{ l('Products with Low Stock') }}
    </h2>        
</div>


<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'products.reorder.index', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('category_id', l('Category')) !!}
    {!! Form::select('category_id', array('0' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('procurement_type', l('Procurement type'), ['class' => 'control-label']) !!}
    {!! Form::select('procurement_type', ['' => l('All', [], 'layouts')] + $product_procurementtypeList, null, array('class' => 'form-control')) !!}
</div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('mrp_type', l('MRP type')) !!}
        {!! Form::select('mrp_type', ['' => l('All', 'layouts')] + $product_mrptypeList, null, array('id' => 'mrp_type', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('products.reorder.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>


<div class="row">

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('stock_control', l('Stock Control')) !!}
    {!! Form::select('stock_control', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('main_supplier_id', l('Main Supplier'), ['class' => 'control-label']) !!}
    {!! Form::select('main_supplier_id', ['' => l('All', [], 'layouts'), '-1' => l('None', [], 'layouts')] + $supplierList, null, array('class' => 'form-control')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>


{{-- Something useful, as promised by Controller ;) --}}
{{--
<div class="container-fluid">
   <div class="row">

      <div class="col-lg-3 col-md-3 col-sm-3">

          @ include('products._panel_block_category_tree')

      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->
      
      <div class="col-lg-9 col-md-9 col-sm-9">

          @include('products_reorder._panel_block_products')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->

   </div>
</div>
--}}

@include('products_reorder._panel_block_products')

@endsection

@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

@endsection


@include('products._modal_view_image')
