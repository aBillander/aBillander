@extends('layouts.master')

@section('title') {{ l('Customer Orders - Create') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

@if ($customer_id ?? 0)
               <a target="_top" class="btn btn-success" href="{{ URL::to('customers/'.$customer_id.'/edit') }}">{{l('View Customer')}}</a>

               <a href="{{ URL::to('customers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customers') }}</a>
@else
               <a target="_top" class="btn btn-success" href="{{ URL::to('customers/create') }}">{{l('New Customer')}}</a>

               <a href="{{ URL::to('customerorders') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customer Orders') }}</a>
@endif
            </div>
            <h2><a href="{{ URL::to('customerorders') }}">{{ l('Customer Orders') }}</a> <span style="color: #cccccc;">/</span> {{ l('New Customer Order') }}</h2>
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">


      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            <div class="panel panel-primary" id="panel_create_order">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Header Data') }}</h3>
               </div>
                {!! Form::open(array('route' => 'customerorders.store', 'id' => 'create_customer_order', 'name' => 'create_customer_order', 'class' => 'form')) !!}

                    @include('customer_orders._form_create')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@endsection
