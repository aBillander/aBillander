@extends('layouts.master')

@section('title') {{ l('Price List Lines') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('pricelists/'.$list->id.'/pricelistlines/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ URL::to('pricelists') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Price Lists') }}</a> 
    </div>
    <h2>
        <a href="{{ URL::to('pricelists') }}">{{ l('Price Lists') }}</a> <span style="color: #cccccc;">/</span> {{ $list->name }} &nbsp; <a href="javascript:void(0);" class="btn btn-lightblue btn-xs"><span class="badge">{{$lines->total()}}</span> {{l('product(s)')}}</a>
    </h2>        
</div>



<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">

          @include('price_list_lines._panel_infos')

      </div>
      
      <div class="col-lg-9 col-md-9 col-sm-9">

          @include('price_list_lines._panel_index')

      </div>
   </div>
</div>

@endsection

@include('layouts/modal_delete')
