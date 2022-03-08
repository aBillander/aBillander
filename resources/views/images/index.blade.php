@extends('layouts.master')

@section('title') {{ l('Images') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('images/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Images') }}
    </h2>        
</div>

<div id="div_taxes">
   <div class="table-responsive">



<!-- -->
<!-- https://stackoverflow.com/questions/22540550/bootstrap-image-popup -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Popup image</button>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <img style="display: block; margin-left: auto; margin-right: auto;" src="/aBillander5/public/imagetest/14-mini_default.jpg" class="img-responsive">
        </div>
    </div>
  </div>
</div>
<!-- -->



   </div>
</div>

@endsection

@include('layouts/modal_delete')
