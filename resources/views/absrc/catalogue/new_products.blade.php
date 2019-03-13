@extends('absrc.layouts.master')

@section('title') {{ l('Catalogue') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">


            </div>
            <h2><!-- a href="{{ URL::to('customers') }}">{{ l('Shopping Cart') }}</a> <span style="color: #cccccc;">/</span --> {{ l('New Products') }}</h2>
        </div>








    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-1 col-md-1 col-sm-1">
{{--
          @include('absrc.catalogue._panel_tree')
--}}
      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->
      
      <div class="col-lg-9 col-md-9 col-sm-9">

          @include('absrc.catalogue._panel_new_products')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->

   </div>
</div>
@endsection




@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

@endsection
