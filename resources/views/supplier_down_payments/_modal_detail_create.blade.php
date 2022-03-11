@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="detailModalLabel">{{ l('New Down Payment to Supplier Detail') }} :: <span class="lead well well-sm alert-warning">{{ $downpayment->reference ?: $downpayment->id }}</span> {{ \App\Models\Currency::viewMoneyWithSign($downpayment->amount, $downpayment->currency) }}</h4>
      </div>

      <form id="downpayment_payment_details">

      <div class="modal-body">

          {!! Form::hidden('down_payment_id', $downpayment->id, array('id' => 'down_payment_id')) !!}

                <div class="alert alert-danger" id="error-msg-box" style="display:none">
    
                </div>

    
<div id="customer_pending_vouchers">
</div>

    

      </div>

      <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_downpaymentdetailsSubmit" id="modal_downpaymentdetailsSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Save', [], 'layouts')}}</button>

      </div>

      </form>

    </div>
  </div>
</div>

@endsection


@section('scripts')    @parent


<script type="text/javascript">

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery
{{--
$(function () {
    var $tblChkBox = $("#document_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});
--}}
$("#xxdocument_lines").on("change", function () {
    // if (!$(this).prop("checked")) {
    //    $("#ckbCheckAll").prop("checked", false);
    // }
    calculateSelectedAmount();
});

// check box selection ENDS -->

// $(".selectedamount").on("keyup", function () {
$(".xxxselectedamount").on("keyup", function () {
// $(".selectedamount").keyup( function () {
    // if (!$(this).prop("checked")) {
    //    $("#ckbCheckAll").prop("checked", false);
    // }
    calculateSelectedAmount();
});

        function calculateSelectedAmount() {
            var total = 0;
            $('.xcheckbox:checked').each(function(index,value){

                total += parseFloat($(this).closest('tr').find('.selectedamount').val().replace(',', '.'));

            });

            $('#balance').html(total);
        }


{{-- *************************************** --}}



          $("body").on('click', "#modal_downpaymentdetailsSubmit", function( event ) {

            var url = "{{ route('supplier.downpayments.details.store', $downpayment->id) }}";
            var token = "{{ csrf_token() }}";
            var payload = $("#downpayment_payment_details").serialize();

            $('#error-msg-box').hide();

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function( response ){

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    if( response.success != 'OK' ) 
                    {
                        $('#error-msg-box').html(response.message);

                        $('#error-msg-box').show();

                        return ;
                    }

                    $('#detailModal').modal('toggle');

            // $('#priceruleModal').modal({show: true});

                    showAlertDivWithDelay("#msg-detail-success");

                    getChequeDetails();
                }
            });


            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();

        });

</script>

@endsection


@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">


<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

<style>

.modal-content {
  overflow:hidden;
}

/*
See: https://coreui.io/docs/components/buttons/ :: Brand buttons
*/
.btn-behance {
    color: #fff;
    background-color: #1769ff;
    border-color: #1769ff;
}

</style>

@endsection




