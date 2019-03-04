
@section('modals')    @parent

<div class="modal" id="modal_document_availability" tabindex="-1" role="dialog">
   <div class="modal-dialog xmodal-lg" style="width: 99%; max-width: 1000px;">
      <div class="modal-content" id="document_availability_content">




      </div>
   </div>
</div>

@endsection


@section('scripts')    @parent

<script type="text/javascript">


$(document).ready(function() {

          $(document).on('click', '.show-stock-availability', function(evnt) {

              // What to do? Let's see:
              var id = $(this).attr('data-id');

               var panel = $("#document_availability_content");
               var url = "{{ route( $model_path.'.availability.modal', [':id'] ) }}";

               panel.html('');
               panel.addClass('loading');

               url = url.replace(':id', id);

               $.get(url, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();

                     // Populate form
                     // getProductLineData( selector );

               }, 'html');


              $('#modal_document_availability').modal({show: true});

              return false;

          });

});

</script>

@endsection
