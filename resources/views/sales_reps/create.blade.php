@extends('layouts.master')

@section('title') {{ l('Sales Representatives - Create') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ URL::to('salesreps') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Sales Representatives') }}</a>
            </div>
            <h2><a href="{{ URL::to('salesreps') }}">{{ l('Sales Representatives') }}</a> <span style="color: #cccccc;">/</span> {{ l('New Sales Representative') }}</h2>
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
                {!! Form::open(array('route' => 'salesreps.store', 'id' => 'create_salesrep', 'name' => 'create_salesrep', 'class' => 'form')) !!}
                   
                    @include('sales_reps._form_create')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@endsection