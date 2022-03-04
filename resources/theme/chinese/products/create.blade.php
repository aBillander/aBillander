@extends('layouts.master')

@section('title') {{ l('Products - Create') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ URL::to('products') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Products') }}</a>
            </div>
            <h2><a href="{{ URL::to('products') }}">{{ l('Products') }}</a> <span style="color: #cccccc;">/</span> {{ l('New Product') }}</h2>
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_generales" href="" class="list-group-item active" onClick="return false;">
               <i class="fa fa-edit"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            <div class="panel panel-info" id="panel_generales">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>
                {!! Form::open(array('route' => 'products.store', 'id' => 'create_product', 'name' => 'create_product', 'class' => 'form')) !!}

                    @include('products._form_create')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@endsection
