
@section('modals')    @parent

<div class="modal fade" id="modalproductBOMs" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h4 class="modal-title" id="modalproductBOMsLabel">{{ l('BOMs with this Product', 'productboms') }}</h4>

           </div>

           <div class="modal-body" id="panel_product_boms">



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

// $('#modalproductBOMs').modal({show: true});

        function loadproductBOMs() {
           
           var panel = $("#panel_product_boms");
           var url = "{{ route('product.getproductboms', $product->id) }}";

           panel.addClass('loading');
//              alert('ddddefgghhnddd');

           $.get(url, {}, function(result){
//              alert(result);
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
           }, 'html');

        }

          $('body').on('click', '.show-product-boms', function(evnt) {

               loadproductBOMs();
//              alert('ddddddd');
              $('#modalproductBOMs').modal({show: true});
              return false;
          });

        });

</script>

@endsection
