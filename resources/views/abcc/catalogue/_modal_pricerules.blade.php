
@section('modals')    @parent

<div class="modal fade" id="myModalShowPriceRules" role="dialog">
   <div class="modal-dialog modal-lg">
       <div class="modal-content" id="pricerule-lines">


      </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

<script>

    $(document).ready(function () {

            $('.show-pricerules').click(function (evnt) {

               var panel = $("#pricerule-lines");
               var secure_key = $(this).attr('data-id');
               var url = "{{ route('abcc.catalogue.product.pricerules', [":id"]) }}";

               url = url.replace(":id", secure_key);

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');
                     $("[data-toggle=popover]").popover();
                     
               }, 'html').done( function() { 

                    $('#myModalShowPriceRules').modal({show: true});

                });

               return false;

           });

    });

</script>

@endsection