{{--
@include('errors.list')
--}}

{!! Form::open(array('route' => array('pricelists.import.process', $pricelist->id), 'files' => true)) !!}

<div class="panel panel-info" id="panel_import_pricelists">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Import Price List File') }}</h3>
   </div>
   <div class="panel-body">


          @include('imports._form_upload_price_lists')   

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-upload"></i>
         &nbsp; {{l('Import', [], 'layouts')}}
      </button>
   </div>



   <!-- div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div -->

</div>

{!! Form::close() !!}


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

@endsection