@extends('layouts.master')

@section('title') {{ l('Companies - Edit') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- a href="{{ URL::to('companies') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Companies') }}</a -->
            </div>
            <h2><a href="{{ URL::to('#') }}">{{ l('Companies') }}</a> <span style="color: #cccccc;">/</span> {{ $company->name_fiscal }}</h2>
        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_generales" href="" class="list-group-item active" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
         </div>

         <div class="list-group"><?php $img = \App\Context::getContext()->company->company_logo ?>
@if ( $img )
            <img src="{{ URL::to( \App\Company::$company_path . $img ) }}" class="img-responsive center-block" style="border: 1px solid #dddddd;">
@else
            
@endif
         </div>

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
         {!! Form::model($company, array('method' => 'PATCH', 'route' => array('companies.update', $company->id), 'files' => true)) !!}
            <div class="panel panel-info" id="panel_generales">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Main Data') }} ({{$company->id}})</h3>
               </div>
               
              @include('companies._form')
              
            </div>
          {!! Form::close() !!}
      </div>
   </div>
</div>
@stop


@section('scripts') @parent 

<script type="text/javascript">
$(function() {

  // See: https://www.abeautifulsite.net/whipping-file-inputs-into-shape-with-bootstrap-3
  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }

      });
  });
  
});
</script>

@stop