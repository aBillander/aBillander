@extends('layouts.master')

@section('title') {{ l('Customer Invoices - Create') }} @parent @stop


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

               <a href="{{ URL::to($model_path) }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customer Invoices') }}</a>
@endif
            </div>
            <h2><a class="btn btn-sm {{ $model_class::getBadge('a_class') }}" href="{{ URL::to($model_path) }}" title="{{ l('Customer Invoices') }}"><i class="fa {{ $model_class::getBadge('i_class') }}"></i></a> <span style="color: #cccccc;">/</span> {{ l('New Customer Invoice') }}</h2>
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">


      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            <div class="panel panel-primary" id="panel_create_{{ $model_snake_case }}">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Header Data') }}</h3>
               </div>
                {!! Form::open(array('route' => $model_path.'.store', 'id' => 'create_'.$model_snake_case, 'name' => 'create_'.$model_snake_case, 'class' => 'form')) !!}

                    @include($view_path.'._form_document_create')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@endsection
