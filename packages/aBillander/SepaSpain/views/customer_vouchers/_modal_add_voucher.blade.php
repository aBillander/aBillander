
@section('modals')    @parent

<div class="modal" id="modal_add_voucher" tabindex="-1" role="dialog">
   <div class="modal-dialog xmodal-lg" style="width: 99%; max-width: 1000px;">
      <div class="modal-content" id="add_voucher_form">




      </div>
   </div>
</div>

@endsection


@section('scripts')    @parent

<script type="text/javascript">


$(document).ready(function() {

          $(document).on('click', '.add-voucher-to-sdd', function(evnt) {

            // Load form first
              var id = $(this).attr('data-id');

               var panel = $("#add_voucher_form");
               var url = "{{ route('sepasp.directdebit.add.voucher.form', [':id']) }}";

               url = url.replace(':id', id);

               panel.html('');
               panel.addClass('loading');

               $.get(url, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();

                     // Populate form
                     // getProductLineData( selector );

               }, 'html');


              $('#modal_add_voucher').modal({show: true});
              $("#line_quantity").focus();

              return false;

          });


        $(document).on('click', ".add-this-voucher-to-sdd", function() {

            var id = $(this).attr('data-sdd-id');
            var url = "{{ route('sepasp.directdebit.add.voucher') }}";
            var token = "{{ csrf_token() }}";

            // alert(id); location.reload();  return;

            var payload = { 
                              voucher_id : $('#voucher_id').val(),
                              sdd_id : id
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(response){
                    
                    location.reload();

                    // console.log(response);
                }
            });

        });

});

</script>

@endsection
