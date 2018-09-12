@extends('layouts.master')

@section('title') {{ l('Production Sheets - Show') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

        <a href="{{ URL::to('productionsheets/'.$sheet->id) }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back', 'layouts') }}</a>
    </div>
    <h2>
        <a href="{{ URL::to('productionsheets') }}">{{ l('Production Sheets') }}</a> <span style="color: #cccccc;">/</span> 
        <a href="{{ URL::to('productionsheets/'.$sheet->id) }}">{{ abi_date_form_short($sheet->due_date) }}</a> <span style="color: #cccccc;">::</span> {{ l('Picking List') }}
    </h2>        
</div>

    </div>
</div> 


<div class="container-fluid">

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">

                    @include('production_sheets._panel_pickinglist')

                    
      </div>
   </div>

</div>


@endsection


@section('scripts') @parent 

<!-- script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script -->

@endsection
