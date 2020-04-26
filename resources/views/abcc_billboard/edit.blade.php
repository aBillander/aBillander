@extends('layouts.master')

@section('title') {{ l('ABCC Billboard - Edit') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

        <a href="#preview" class="btn btn-sm btn-success" 
            title="{{l('Billboard Preview')}}"><i class="fa fa-eye"></i> {{l('Billboard Preview')}}</a>

            </div>
            <h2>{{ l('ABCC Billboard') }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-12 col-md-12 col-sm-12">

          @include('abcc_billboard._form')

      </div>

   </div>

   <a id="preview"></a>

   <h2>{{ l('Billboard Preview') }}</h2>

   <div class="row">

      <div class="col-lg-12 col-md-12 col-sm-12">

          @include('abcc._billboard')

      </div>

   </div>
</div>

@include('layouts/back_to_top_button')

@endsection

@section('scripts') 
<script type="text/javascript">

  // See: https://www.abeautifulsite.net/whipping-file-inputs-into-shape-with-bootstrap-3
  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

   $(document).ready(function() {

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
</script>

{{-- Use CKEditor. See: https://artisansweb.net/install-use-ckeditor-laravel/ --}}
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'caption', {
        height: 80,
        allowedContent: true,
    } );
    CKEDITOR.replace( 'caption_default' );
/*
CKEDITOR Allow everything (disable ACF)
https://ckeditor.com/docs/ckeditor4/latest/guide/dev_acf.html
https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html
https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_filter_allowedContentRules.html
https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html#cfg-allowedContent
*/
</script>

@endsection

{{--
@section('styles') 

<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" type="text/css" rel="stylesheet" />

@endsection
--}}
