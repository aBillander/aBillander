
@section('modals')    @parent

<div class="modal fade" id="modalBOMproducts" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h4 class="modal-title" id="modalBOMproductsLabel">{{ l('Products for this BOM') }}</h4>

           </div>

           <div class="modal-body" id="panel_bom_products">



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

// $('#modalBOMproducts').modal({show: true});

        function loadBOMProducts() {
           
           var panel = $("#panel_bom_products");
           var url = "{{ route('productbom.getproducts', $bom->id) }}";

           panel.addClass('loading');
//              alert('ddddefgghhnddd');

           $.get(url, {}, function(result){
//              alert(result);
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
           }, 'html');

        }

          $('body').on('click', '.show-bom-products', function(evnt) {

               loadBOMProducts();
//              alert('ddddddd');
              $('#modalBOMproducts').modal({show: true});
              return false;
          });

        });

</script>

@endsection
