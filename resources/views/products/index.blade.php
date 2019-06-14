@extends('layouts.master')

@section('title') {{ l('Products') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

{!! Form::model(Request::all(), array('route' => 'products.index', 'method' => 'GET', 
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


        <a href="{{ URL::to('products/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <!-- a href="{{ route('products.import') }}" class="btn btn-sm btn-warning" 
                title="{{l('Import', [], 'layouts')}}"><i class="fa fa-ticket"></i> {{l('Import', [], 'layouts')}}</a -->



        <div class="btn-group xopen">
          <a href="#" class="btn btn-warning btn-sm"><i class="fa fa-ticket"></i> {{l('Import', [], 'layouts')}}</a>
          <a href="#" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ route('products.import') }}"><i class="fa fa-file-excel-o"></i> &nbsp; {{l('File', [], 'layouts')}}</a>
            </li>

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
            <li class="divider"></li>
            <li><a href="{{ route('fsxproducts.index') }}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> &nbsp; {{l('FactuSOL')}}</a>
            </li>
@endif
          </ul>
        </div>




        <a href="{{ route('products.export') }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

        <a href="{{ route('wproducts.import.product.images') }}" class="btn btn-sm btn-info" style="display:none;"
                title="{{l('Import', [], 'layouts')}}"><i class="fa fa-image"></i> </a>
    </div>
    <h2>
        {{ l('Products') }}
    </h2>        
</div>


<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'products.index', 'method' => 'GET')) !!}

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
    {!! Form::label('stock', l('Stock')) !!}
    {!! Form::select('stock', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('category_id', l('Category')) !!}
    {!! Form::select('category_id', array('0' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('procurement_type', l('Procurement type'), ['class' => 'control-label']) !!}
    {!! Form::select('procurement_type', ['' => l('All', [], 'layouts')] + $product_procurementtypeList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="display: none">
    {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
    {!! Form::select('active', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('products.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>

{{--
<div id="div_products">
   <div class="table-responsive">

@if ($products->count())
<table id="products" class="table table-hover">
    <thead>
        <tr>
			<th>{{l('ID', [], 'layouts')}}</th>
      <th>{{ l('Reference') }}</th>
      <th>{{ l('Procurement type') }}</th>
      <th colspan="2">{{ l('Product Name') }}</th>
			<!-- th>{{ l('Measure Unit') }}</th -->
            <th>{{ l('Stock') }}</th>
            <!-- th>{{ l('Cost Price') }}</th -->
            <th>{{ l('Customer Price') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ?
                                    l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                    l('Prices are entered exclusive of tax', [], 'appmultilang') }}">
                    <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
            <!-- th>{{ l('Tax') }}</th -->
            <!-- th>{{ l('Tax') }} (%)</th -->
            <th>{{ l('Category') }}</th>
            <th>{{ l('Stock') }}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th class="text-right"> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($products as $product)
		<tr>
			<td>{{ $product->id }}</td>
			<td title="{{ $product->id }}">@if ($product->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else {{ $product->reference }}
                @endif</td>
      <td>{{ $product->procurement_type }}</td>

      <td>
@php
  $img = $product->getFeaturedImage();
@endphp
@if ($img)
              <a class="view-image" data-html="false" data-toggle="modal" 
                     href="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-large_default' . '.' . $img->extension ) }}"
                     data-content="{{l('You are going to view a record. Are you sure?')}}" 
                     data-title="{{ l('Product Images') }} :: {{ $product->name }} " 
                     data-caption="({{$img->id}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('View Image')}}">

                      <img src="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">
              </a>
@endif
      </td>

      <td>{{ $product->name }}</td>
			<!-- td>{{ $product->measureunit->name }}</td -->
            <td>{{ $product->as_quantity('quantity_onhand') }}</td>
            <!-- td>{{ $product->as_price('cost_price') }}</td -->
            <td>{{ $product->displayPrice }}</td>
            <!-- td>{{ $product->tax->name }}</td -->
            <!-- td>{{ $product->as_percentable($product->tax->percent) }}</td -->
            <td>@if (isset($product->category)) {{ $product->category->name }} @else - @endif</td>
            <td class="text-center">{{ $product->as_quantity('quantity_onhand') }}</td>
            <td class="text-center">
                @if ($product->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $product->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
			<td class="text-center">@if ($product->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
           <td class="text-right button-pad">
                @if (  is_null($product->deleted_at))
                <a class="btn btn-sm btn-success" href="{{ URL::to('products/' . $product->id . '/duplicate') }}" title="{{l('Duplicate', [], 'layouts')}}"><i class="fa fa-copy"></i></a>
                <a class="btn btn-sm btn-warning" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('products/' . $product->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Products') }} :: ({{$product->id}}) {{{ $product->name }}}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('products/' . $product->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('products/' . $product->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
		</tr>
	@endforeach
    </tbody>
</table>
{!! $products->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>
--}}



<div class="container-fluid">
   <div class="row">

      <div class="col-lg-3 col-md-3 col-sm-3">

          @include('products._panel_block_category_tree')

      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->
      
      <div class="col-lg-9 col-md-9 col-sm-9">

          @include('products._panel_block_products')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->

   </div>
</div>


@endsection

@include('layouts/modal_delete')

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
