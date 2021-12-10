
@section('modals')    @parent

<div class="modal fade" id="modalproductActivateLotTracking" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" xmodal-lg">
       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h4 class="modal-title" id="modalproductActivateLotTrackingLabel">{{ l('Activate Lot tracking') }}</h4>

           </div>

           <div id="panel_product_activate_lot_tracking">


           </div>

       </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts')    @parent 

<script>


        $(document).ready(function() {

// $('#modalproductActivateLotTracking').modal({show: true});

        function loadproductActivateLotTrackingForm() {
           
           var panel = $("#panel_product_activate_lot_tracking");
           var url = "{{ route('product.lottracking', $product->id) }}";

           panel.addClass('loading');
//              alert('ddddefgghhnddd');

           $.get(url, {}, function(result){
//              alert(result);
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
           }, 'html');

        }

          $('body').on('click', '.activate-lot-tracking', function(evnt) {

               loadproductActivateLotTrackingForm();
//              alert('ddddddd');

              $('#modalproductActivateLotTracking').modal({show: true});

              // Seems to need a delay
              setTimeout(function(){ $("[data-toggle=popover]").popover(); 

  $(function() {
    $( "#lottracking_manufactured_at_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });


  $(function() {
    $( "#lottracking_expiry_at_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
// alert('xxx');
              }, 2000);
              // See: https://stackoverflow.com/questions/50960978/call-js-function-after-div-is-loaded

              return false;
          });

        });

</script>

@endsection
