
{{-- @include('errors.list') --}}

{!! Form::open(array('route' => array('products.images.store', $product->id), 'files' => true)) !!}
<input type="hidden" value="images" name="tab_name" id="tab_name">

<div class="panel panel-primary" id="panel_images">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Images') }}</h3>
   </div>
   <div class="panel-body">

<!-- Images -->

          @include('products._form_create_image')   

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

        <div class="row">
         <hr style="height:2px; border: 1px solid #eeeeee;">
        </div>
        <div class="row">
              <div class="form-group col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

    <div id="div_images">
       <div class="table-responsive">

    <table id="images" class="table table-hover">
      <thead>
        <tr>
          <th>{{l('ID', [], 'layouts')}}</th>
          <th>{{ l('Image') }}</th>
          <th>{{ l('Caption') }}</th>
          <th>{{l('Position', [], 'layouts')}}</th>
          <th class="text-center">{{l('Is Featured?')}}</th>
          <th class="text-right"> </th>
        </tr>
      </thead>
      <tbody>
      @if ($product->images->count())
         @foreach ($product->images as $img)
           <tr style="vertical-align:middle;">
               <td>{{ $img->id }}</td>
               <td>

              <a class="view-image" data-html="false" data-toggle="modal" 
                     href="{{ URL::to( \App\Image::pathProducts() . $img->getImageFolder() . $img->id . '-large_default' . '.' . $img->extension ) }}"
                     data-content="{{l('You are going to view a record. Are you sure?')}}" 
                     data-title="{{ l('Product Images') }} :: {{ $product->name }} " 
                     data-caption="({{$img->id}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('View Image')}}">

                      <img src="{{ URL::to( \App\Image::pathProducts() . $img->getImageFolder() . $img->id . '-small_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">
              </a>

               </td>
               <td>{{ $img->caption }}</td>
               <td>{{ $img->position }}</td>

               <td class="text-center">@if ($img->is_featured) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

               <td class="text-right">

                <a class="btn btn-sm btn-warning" href="{{ URL::to('products/' . $product->id.'/images/' . $img->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                     href="{{ URL::to('products/' . $product->id.'/images/' . $img->id ) }}" 
                     data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                     data-title="{{ l('Product Images') }} :: ({{$img->id}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

                </td>
           </tr>
         @endforeach
      @else
           <tr style="vertical-align:middle;">
               <td colspan=5>
                 <div class="alert alert-warning alert-block">
                     <i class="fa fa-warning"></i>
                     {{l('No records found', [], 'layouts')}}
                 </div>
                </td>
           </tr>
      @endif
        </tbody>
    </table>

       </div>
    </div>




              </div>
        </div>

        <div class="row">
        </div>

<!-- Images ENDS -->

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


@include('products._modal_view_image')


@include('layouts/modal_delete')
