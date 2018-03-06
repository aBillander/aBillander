
@section('modals')    @parent

<div class="modal fade" id="modalCustomerOrderSummary" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h4 class="modal-title" id="modalCustomerOrderSummaryLabel">{{ l('Customer Order Summary') }}</h4>

                {!! Form::hidden('current_production_sheet_id', $sheet->id, ['id' => 'current_production_sheet_id_summary']) !!}

           </div>

           <div class="modal-body" id="panel_customer_order_summary">



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

// $('#modalCustomerOrderSummary').modal({show: true});

        function loadCustomerOrderSummary() {
           
           var id = $("#current_production_sheet_id_summary").val();
           var panel = $("#panel_customer_order_summary");
           var url_raw = "{{ route('productionsheet.getCustomerOrdersSummary', 'dummy') }}";
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

          $('body').on('click', '.show-order-products-summary', function(evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');

               loadCustomerOrderSummary();
//              alert('ddddddd');
              $('#modalCustomerOrderSummary').modal({show: true});
              return false;
          });

        });

</script>

@endsection
