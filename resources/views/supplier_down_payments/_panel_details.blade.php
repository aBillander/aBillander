
<div id="panel_details">



            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Details') }}</h3>
               </div>

              <div class="panel-body">


<div id="msg-detail-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-detail-success-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>


<div id="content_details"></div>

              </div>

            </div>
               
</div>

{{-- --}}
    @include('supplier_down_payments/_modal_detail_create')
{{-- --}}

@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {


        $(document).on('click', '.create-downpaymentdetail', function(evnt) {

            // Load Content
            getSupplierPendingVouchers( {{ $downpayment->supplier_id }} );

            // Open popup
            $('#detailModal').modal({show: true});


            return false;
        });
      

   });

//      $(window).on('hashchange',function(){
//      page = window.location.hash.replace('#','');
//      if (page == 'details') getDownpaymentDetails();
//    });

    function getDownPaymentDetails(){

           $('#content_details').addClass('loading');

      $.ajax({
        url: '{{ route( 'supplier.downpayment.getdetails', [$downpayment->id] ) }}',
        data: {}
      }).done(function(data){
        $('#content_details').html(data);

                 $('#content_details').removeClass('loading');
                 $("[data-toggle=popover]").popover();

                 sortableDownpaymentDetails();
      });
    }


        function sortableDownpaymentDetails() {

          // Sortable :: http://codingpassiveincome.com/jquery-ui-sortable-tutorial-save-positions-with-ajax-php-mysql
          // See: https://stackoverflow.com/questions/24858549/jquery-sortable-not-functioning-when-ajax-loaded
          $('.sortable').sortable({
              cursor: "move",
              update:function( event, ui )
              {
                  $(this).children().each(function(index) {
                      if ( $(this).attr('data-sort-order') != ((index+1)*10) ) {
                          $(this).attr('data-sort-order', (index+1)*10).addClass('updated');
                      }
                  });

                  saveNewDownpaymentPositions();
              }
          });

        }

        function saveNewDownpaymentPositions() {
            var positions = [];
            var token = "{{ csrf_token() }}";

            $('.updated').each(function() {
                positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
                $(this).removeClass('updated');
            });

            $.ajax({
                url: "{{ route('supplier.downpayment.sortlines') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'POST',
                dataType: 'json',
                data: {
                    positions: positions
                },
                success: function (response) {
                    console.log(response);
                }
            });
        }


    function getSupplierPendingVouchers( supplier_id ) {

           $('#supplier_pending_vouchers').addClass('loading');

      $.ajax({
        url: '{{ route( 'supplier.vouchers.pending', [$downpayment->supplier_id] ) }}',
        data: {}
      }).done(function(data){
        $('#supplier_pending_vouchers').html(data);

                 $('#supplier_pending_vouchers').removeClass('loading');
                 $("[data-toggle=popover]").popover();
      });
    }

</script>
@endsection
