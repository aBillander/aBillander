@extends('layouts.master')

@section('title') {{ l('Warehouses - Edit') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ route('warehouse.inventory', [$warehouse->id]) }}" class="btn btn-success"><i class="fa fa-th-list"></i> {{ l('Products') }}</a>

                <a href="{{ URL::to('warehouses') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Warehouses') }}</a>
            </div>
            <h2><a href="{{ URL::to('warehouses') }}">{{ l('Warehouses') }}</a> <span style="color: #cccccc;">/</span> {{ $warehouse->address->name_commercial }}</h2>
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_generales" href="" class="list-group-item active" onClick="return false;">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            <div class="panel panel-info" id="panel_generales">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>
                {!! Form::model($warehouse, array('method' => 'PATCH', 'route' => array('warehouses.update', $warehouse->id))) !!}

                    @include('warehouses._form')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@endsection