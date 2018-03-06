
@section('modals')    @parent

<div class="modal fade" id="modalCustomerOrderProducts" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h4 class="modal-title" id="modalCustomerOrderProductsLabel">{{ l('Products of Customer Order') }}: <span id="customer_order_id"></span></h4>

           </div>

           <div class="modal-body" id="panel_customer_order_products">



           </div>

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>

           </div>

       </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts')    @parent 

<script>


        $(document).ready(function() {

// $('#modalCustomerOrderProducts').modal({show: true});

        function loadCustomerOrderProducts( id ) {
           
           var panel = $("#panel_customer_order_products");
           var url_raw = "{{ route('customerorder.getlines', 'dummy') }}";
           var url = url_raw.replace('dummy', id);

           panel.addClass('loading');
//              alert('ddddefgghhnddd');

           $.get(url, {}, function(result){
//              alert(result);
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
           }, 'html');

        }

          $('body').on('click', '.show-customer-order-products', function(evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');

              $('#customer_order_id').text(reference);
               loadCustomerOrderProducts( id );
//              alert('ddddddd');
              $('#modalCustomerOrderProducts').modal({show: true});
              return false;
          });

        });

</script>

@endsection
