@extends('layouts.master')

@section('title') {{ l('Stock Count Lines') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

@if ( !$list->processed )
        <a class="btn btn-sm btn-grey" href="{{ URL::route('stockcounts.import', [$list->id] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-upload"></i></a>

        <a class="btn btn-sm btn-info update-warehouse-stock" data-html="false" data-toggle="modal" 
                href="{{ URL::route('stockcount.warehouse.update', [$list->id] ) }}" 
                data-content="{{l('You are going to UPDATE the Stock of Products in Warehouse <i><u>:ws</u></i>. Are you sure?', ['ws' => $list->warehouse->name])}}" 
                data-wsname="{{ $list->warehouse->name }}" 
                data-title="{{ l('Stock Counts') }} :: ({{$list->id}}) {{ $list->name }}" 
                onClick="return false;" title="{{l('Process Stock Count')}}"><i class="fa fa-superpowers"></i> {{-- l('Process Stock Count') --}}</a>

        <a href="{{ URL::to('stockcounts/'.$list->id.'/stockcountlines/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
@endif

        <a class="btn btn-sm btn-grey" href="{{ URL::route('stockcounts.export', [$list->id] ) }}" title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ URL::to('stockcounts') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Stock Counts') }}</a> 
    </div>
    <h2>
        <a href="{{ URL::to('stockcounts') }}">{{ l('Stock Counts') }}</a> <span style="color: #cccccc;">/</span> {{ $list->name }} &nbsp; <a href="javascript:void(0);" class="btn btn-lightblue btn-xs"><span class="badge">{{$lines->total()}}</span> {{l('product(s)')}}</a>
    </h2>        
</div>



<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">

          @include('stock_count_lines._panel_infos')

      </div>
      
      <div class="col-lg-9 col-md-9 col-sm-9">

          @include('stock_count_lines._panel_index')

      </div>
   </div>
</div>

@endsection


@include('stock_counts/_modal_update_warehouse_stock')

@include('layouts/modal_delete')
