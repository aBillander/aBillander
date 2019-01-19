
@section('modals')    @parent

<div class="modal fade" id="myModalShowPayments" role="dialog">
   <div class="modal-dialog modal-lg">
       <div class="modal-content" id="payment-lines">


      </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

<script>

    $(document).ready(function () {

            $('.show-payments').click(function (evnt) {

               var panel = $("#payment-lines");
               var id = $(this).attr('data-id');
               var url = "{{ route('abcc.invoices.vouchers', [":id"]) }}";

               url = url.replace(":id", id);

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');
                     $("[data-toggle=popover]").popover();
                     
               }, 'html').done( function() { 

                    $('#myModalShowPayments').modal({show: true});

                });

               return false;

           });

    });

</script>

@endsection