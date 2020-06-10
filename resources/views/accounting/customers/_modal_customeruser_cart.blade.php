
@section('modals')    @parent

<div class="modal fade" id="myModalCustomerUserCart" tabindex="-1" role="dialog" aria-labelledby="myModalCustomerUserCartLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <div class="modal-header alert-info">
            <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="customeruserModalLabel">{{ l('Shopping Cart') }} :: <span id="customeruser-name"></span></h4>
          </div>
          <div class="modal-body" id="customeruser-cart">

          </div>

          <div class="modal-footer">

                   <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>

          </div>


      </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

<script>

    $(document).ready(function () {

            $(document).on('click', '.view-customeruser-cart', function(evnt) {

               var panel = $("#customeruser-cart");
               var id = $(this).attr('data-id');
               var name = $(this).attr('data-name');
               var url = "{{ route('customer.cart', [":id"]) }}";

               $('#customeruser-name').html(name);

               url = url.replace(":id", id);

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');
                     $("[data-toggle=popover]").popover();
                     
               }, 'html').done( function() { 

                    $('#myModalCustomerUserCart').modal({show: true});

                });

               return false;

           });

    });

</script>

@endsection