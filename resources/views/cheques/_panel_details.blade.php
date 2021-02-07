
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
    @include('cheques/_modal_detail_create')
{{-- --}}

@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {


        $(document).on('click', '.create-chequedetail', function(evnt) {

            // Load Content
            getCustomerPendingVouchers( {{ $cheque->customer_id }} );

            // Open popup
            $('#detailModal').modal({show: true});


            return false;
        });
      

   });

//      $(window).on('hashchange',function(){
//      page = window.location.hash.replace('#','');
//      if (page == 'details') getChequeDetails();
//    });

    function getChequeDetails(){

           $('#content_details').addClass('loading');

      $.ajax({
        url: '{{ route( 'cheque.getdetails', [$cheque->id] ) }}',
        data: {}
      }).done(function(data){
        $('#content_details').html(data);

                 $('#content_details').removeClass('loading');
                 $("[data-toggle=popover]").popover();

                 sortableChequeDetails();
      });
    }


        function sortableChequeDetails() {

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

                  saveNewChequePositions();
              }
          });

        }

        function saveNewChequePositions() {
            var positions = [];
            var token = "{{ csrf_token() }}";

            $('.updated').each(function() {
                positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
                $(this).removeClass('updated');
            });

            $.ajax({
                url: "{{ route('cheque.sortlines') }}",
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


    function getCustomerPendingVouchers( customer_id ) {

           $('#customer_pending_vouchers').addClass('loading');

      $.ajax({
        url: '{{ route( 'customer.vouchers.pending', [$cheque->customer_id] ) }}',
        data: {}
      }).done(function(data){
        $('#customer_pending_vouchers').html(data);

                 $('#customer_pending_vouchers').removeClass('loading');
                 $("[data-toggle=popover]").popover();
      });
    }

</script>
@endsection
