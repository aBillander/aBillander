
@section('modals')    @parent

<div class="modal fade" id="modalProductionOrderShow" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

           <div id="panel_production_order_show">



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

// $('#modalProductionOrderShow').modal({show: true});

        function loadProductionOrderShow( id ) {
           
           var panel = $("#panel_production_order_show");
           var url_raw = "{{ route('productionorder.getorder', 'dummy') }}";
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

          $('body').on('click', '.show-production-order-products', function(evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');

              $('#production_order_id').text(id);
               loadProductionOrderShow( id );
//              alert('ddddddd');
              $('#modalProductionOrderShow').modal({show: true});
              return false;
          });

        });

</script>

@endsection
