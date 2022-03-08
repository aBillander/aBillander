
@section('modals')    @parent

<div class="modal fade" id="modal_product_consumption" tabindex="-1" role="dialog">
   <div class="modal-dialog xmodal-lg" style="width: 99%; max-width: 1000px;">
      <div class="modal-content" id="product_consumption">




      </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts')    @parent

<script type="text/javascript">

    $(document).ready(function () {

            $(document).on('click', '.show-supplier-consumption', function (evnt) {

               var panel = $("#product_consumption");
               var id = $(this).attr('data-id');
               var supplier_id = $("#supplier_id").val();
               var url = "{{ route('supplier.product.consumption', [":supplier_id", ":id"]) }}";

               url = url.replace(":id", id);
               url = url.replace(":supplier_id", supplier_id);

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');
                     $("[data-toggle=popover]").popover();
                     
               }, 'html').done( function() { 

                    $('#modal_product_consumption').modal({show: true});

                });

               return false;

           });

    });

</script>

@endsection
