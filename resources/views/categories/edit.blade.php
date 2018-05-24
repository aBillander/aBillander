@extends('layouts.master')

@section('title') {{ l('Categories - Edit') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            @if ( $parentId>0 )
            <div class="pull-right">
                <a href="{{ URL::to('categories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
                <a href="{{ URL::to('categories/'.$parent->id.'/subcategories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to :name', ['name' => $parent->name]) }}</a>
            </div>
            <h2><a href="{{ URL::to('categories') }}">{{ l('Product Categories') }}</a> <span style="color: #cccccc;">/</span> <a href="{{ URL::to('categories/'.$parent->id.'/subcategories') }}">{{ $parent->name }}</a> <span style="color: #cccccc;">/</span> {{ l('Edit Category') }}</h2>
            @else
            <div class="pull-right">
                <a href="{{ URL::to('categories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
            </div>
            <h2><a href="{{ URL::to('categories') }}">{{ l('Product Categories') }}</a> <span style="color: #cccccc;">/</span> {{ l('Edit Category') }}</h2>
            @endif
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
            <a id="b_internet" href="#internet" class="list-group-item">
               <i class="fa fa-cloud"></i>
               &nbsp; {{ l('Internet') }}
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include('categories._panel_main_data')

          @include('categories._panel_internet')

      </div>
   </div>
</div>
@stop

@section('scripts') 
<script type="text/javascript">
   function route_url()
   {
      $("#panel_main_data").hide();
      $("#panel_internet").hide();

      $("#b_main_data").removeClass('active');
      $("#b_internet").removeClass('active');
      
      if(window.location.hash.substring(1) == 'internet')
      {
         $("#panel_internet").show();
         $("#b_internet").addClass('active');
      }
      else  
      {
         $("#panel_main_data").show();
         $("#b_main_data").addClass('active');
         // document.f_cliente.nombre.focus();
      }
   }
   $(document).ready(function() {
      route_url();
      window.onpopstate = function(){
         route_url();
      }
   });
</script>

@stop
