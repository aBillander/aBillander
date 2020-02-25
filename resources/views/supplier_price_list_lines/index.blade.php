@extends('layouts.master')

@section('title') {{ l('Price List Lines') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ route('suppliers.supplierpricelistlines.create', $supplier->id) }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>


                <a class="btn btn-sm btn-warning" href="{{ URL::route('suppliers.pricelist.import', [$supplier->id] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-ticket"></i> {{l('Import', [], 'layouts')}}</a>

                <a class="btn btn-sm btn-grey" href="{{ URL::route('suppliers.pricelist.export', [$supplier->id] ) }}" title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>


        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Supplier') }}</a> 
    </div>
    <h2><a class="btn btn-sm btn-grey" href="#" title="{{ l('Price List') }}"><i class="fa fa-user"></i></a> <span style="color: #cccccc;">/</span> 

        <a href="{{ route('suppliers.edit', $supplier->id) }}">{{ $supplier->name_regular }}</a> 
        <span class="badge" style="background-color: #3a87ad;" title="{{ $supplier->currency->name }}">{{ $supplier->currency->iso_code }}</span>
        <span style="color: #cccccc;">/</span> 
        {{ l('Price List') }} &nbsp; <a href="javascript:void(0);" class="btn btn-lightblue btn-xs"><span class="badge">{{$lines_total}}</span> {{l('product(s)')}}</a>
    </h2>        
</div>



<div class="container-fluid">
   <div class="row">

{{--
      <div class="col-lg-2 col-md-2 col-sm-2">

          @include('price_list_lines._panel_infos')

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">

          @include('supplier_price_list_lines._panel_index')

      </div>
--}}

          @include('supplier_price_list_lines._panel_index')

   </div>
</div>

@endsection

@include('layouts/modal_delete')
