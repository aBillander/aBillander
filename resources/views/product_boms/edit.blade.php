@extends('layouts.master')

@section('title') {{ l('Bill of Materials - Edit') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a class="btn btn-success show-bom-products" onClick="return false;"><i class="fa fa-link"></i> {{ l('Products') }}</a>

                <a href="{{ URL::to('productboms/'.$bom->id.'/duplicate') }}" class="btn btn-warning"><i class="fa fa-copy"></i> {{ l('Copy BOM') }}</a>

                <a href="{{ URL::to('productboms') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to BOMs') }}</a>
            </div>
            <h2><a href="{{ URL::to('productboms') }}">{{ l('BOMs') }}</a> <span style="color: #cccccc;">/</span> {{ $bom->name }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-3">
   

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include('product_boms._panel_bom')

      </div>

   </div>
</div>

@endsection