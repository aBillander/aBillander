@extends('absrc.layouts.master')

@section('title') {{ l('Documents - Create') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

@if ($customer_id ?? 0)
               <a target="_top" class="btn btn-success" href="{{ URL::to('absrc/customers/'.$customer_id.'/edit') }}">{{l('View Customer')}}</a>

               <a href="{{ URL::to('absrc/customers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customers') }}</a>
@else
               <a target="_top" class="btn btn-success" href="{{ URL::to('absrc/customers/create') }}">{{l('New Customer')}}</a>

               <a href="{{ route($model_path.'.index') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Documents') }}</a>
@endif
            </div>
            <h2><a class="btn btn-sm {{ $model_class::getBadge('a_class') }}" href="{{ URL::to($model_path) }}" title="{{ l('Documents') }}"><i class="fa {{ $model_class::getBadge('i_class') }}"></i></a> <span style="color: #cccccc;">/</span> {{ l('New Document') }}</h2>
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
                {!! Form::open(array('route' => $model_path.'.store', 'id' => 'create_'.$model_snake_case, 'name' => 'create_'.$model_snake_case, 'class' => 'form')) !!}

                    @include($view_path.'._form_document_create')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@endsection
