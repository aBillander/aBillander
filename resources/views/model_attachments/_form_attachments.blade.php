{{-- Case use / Example (see: Supplier Shipping Slips _panel_left_column--}}
{{-- 

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <ul class="list-group">
              <li class="list-group-item" style="color: #333333;background-color: #e7e7e7;border-color: #cccccc;">
                <h4>{{ l('Attachments', 'layouts') }}</h4>
              </li>

              @foreach( $document->attachments as $attachment )
                  <li class="list-group-item">
@php
$label = $attachment->name ?: $attachment->filename;
$label_short = strlen($label) > 11 ? substr($label, 0, 11)."&hellip;" : $label;
@endphp
                      <a href="{{ route( $model_path.'.attachment.show', [$document->id, $attachment->id] ) }}" title="{{l('View Document', 'layouts')}}">

                          {{ $label_short }}

                      </a> 
                      <span class="pull-right">
                        <a class="btn btn-xs alert-danger delete-item" data-html="false" data-toggle="modal" 
                        data-id="{{$attachment->id}}" 
                        href="{{ route( $model_path.'.attachment.destroy', [$document->id, $attachment->id] ) }}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '('.$attachment->id.') '.$label }}" 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                      </span>

                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" data-container="body" 
                                    data-content="{!! $label !!}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
                    
                  </li>
              @endforeach

                  <li class="list-group-item">
                          
                      {!! Form::open(array('route' => [$model_path.'.attachment.store', $document->id], 'title' => l('Upload an Attach Files', 'layouts'), 'class' => '', 'id' => 'add-attachment-action', 'files' => true)) !!}

                      <input type="hidden" value="{{ $document->getClassName() }}"     name="model_class"     id="model_class">
                      <input type="hidden" value="{{ $document->id }}"                 name="model_id"        id="model_id">
                      <input type="hidden" value="{{ $document->document_reference ?: $document->id }}" name="model_reference" id="model_reference">


            <div class="input-group {{ $errors->has('attachment_file') ? 'has-error' : '' }}" style="margin-top: 10px; margin-bottom: 10px;">
                <label class="input-group-btn">
                    <span class="btn btn-blue btn-sm">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="attachment_file" id="attachment_file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control input-sm" readonly>
            </div>

            {{ l('Description') }}
            {!! Form::text('attachment_name', null, array('class' => 'form-control input-sm', 'style' => 'margin-top: 10px; margin-bottom: 10px;', 'id' => 'attachment_name')) !!}

    <div class="text-center">
                      {!! Form::submit(l('Upload File', 'layouts'), array('class' => 'btn btn-sm alert-success')) !!}
    </div>
                      {!! Form::close() !!}

                  </li>
            </ul>

          </div>
          </div>

--}}
{{-- Case use / Example ENDS --}}



@include('layouts/modal_delete')


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
