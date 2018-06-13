@extends('layouts.master')

@section('title') {{ l('Price List Lines') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('pricelists/'.$list->id.'/pricelistlines/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
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
      
      <div class="col-lg-8 col-md-8 col-sm-8">

          @include('price_list_lines._panel_index')

      </div>
   </div>
</div>

@endsection

@include('layouts/modal_delete')
