@extends('layouts.master')

@section('title') {{ l('Categories - Create') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            @if ( $parentId>0 )
            <div class="pull-right">
                <a href="{{ URL::to('categories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
                <a href="{{ URL::to('categories/'.$parent->id.'/subcategories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to :name', ['name' => $parent->name]) }}</a>
            </div>
            <h2><a href="{{ URL::to('categories') }}">{{ l('Product Categories') }}</a> <span style="color: #cccccc;">/</span> <a href="{{ URL::to('categories/'.$parent->id.'/subcategories') }}">{{ $parent->name }}</a> <span style="color: #cccccc;">/</span> {{ l('New Category') }}</h2>
            @else
            <div class="pull-right">
                <a href="{{ URL::to('categories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
            </div>
            <h2><a href="{{ URL::to('categories') }}">{{ l('Product Categories') }}</a> <span style="color: #cccccc;">/</span> {{ l('New Category') }}</h2>
            @endif
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
            <div class="panel panel-info" id="panel_main_data">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }}</h3>
               </div>
                {!! Form::open(array('route' => array('categories.subcategories.store', $parentId), 'id' => 'create_category', 'name' => 'create_category', 'class' => 'form')) !!}

                    @include('categories._form')

                {!! Form::close() !!}
            </div>
      </div>
   </div>
</div>
@stop