
@section('modals')    @parent

<div class="modal fade" id="myModalShowShippingSlips" role="dialog">
   <div class="modal-dialog modal-lg">
       <div class="modal-content" id="shippingslip-lines">


      </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

<script>

    $(document).ready(function () {

            $('.show-shippingslips').click(function (evnt) {

               var panel = $("#shippingslip-lines");
               var secure_key = $(this).attr('data-id');
               var url = "{{ route('abcc.invoices.shippingslips', [":id"]) }}";

               url = url.replace(":id", secure_key);

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');
                     $("[data-toggle=popover]").popover();
                     
               }, 'html').done( function() { 

                    $('#myModalShowShippingSlips').modal({show: true});

                });

               return false;

           });

    });

</script>

@endsection