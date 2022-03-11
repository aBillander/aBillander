@extends('layouts.master')

@section('title') {{ l('Documents - Create') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

               <a href="{{ URL::to('warehouseshippingslips') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Documents') }}</a>
               
            </div>
            <h2><a class="btn btn-sm {{ \App\Models\WarehouseShippingSlip::getBadge('a_class') }}" href="{{ URL::to('warehouseshippingslips') }}" title="{{ l('Documents') }}"><i class="fa {{ \App\Models\WarehouseShippingSlip::getBadge('i_class') }}"></i></a> <span style="color: #cccccc;">/</span> {{ l('New Document') }}</h2>
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">


      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            <div class="panel panel-primary" id="panel_create_document">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Header Data') }}</h3>
               </div>
                {!! Form::open(array('route' => 'warehouseshippingslips.store', 'id' => 'create_warehouse_shipping_slip', 'name' => 'create_warehouse_shipping_slip', 'class' => 'form')) !!}

                    @include('warehouse_shipping_slips._form_document_create')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@endsection
