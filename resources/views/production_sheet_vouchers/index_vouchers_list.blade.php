
<div id="div_payments">
   <div class="table-responsive">

@if ($payments->count())
<table id="payments" class="table table-hover">
    <thead>
        <tr>
      <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll', 'onchange' => 'calculateSelectedAmount()']) !!}</th>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Invoice')}}</th>
      <th>{{l('Invoice Date')}}</th>
            <th>{{l('Customer')}}</th>
            <th>{{l('Due Date')}}</th>
            <th>{{l('Payment Date')}}</th>
            <th class="text-right">{{l('Amount')}}</th>
      <th style="text-transform: none;">{{l('Payment Type', 'customervouchers')}}</th>
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="payment_lines">
    @foreach ($payments as $payment)
        <tr>
@if( $payment->status == 'pending' )
      <td class="text-center warning">{!! Form::checkbox('payment_group[]', $payment->id, false, ['class' => 'case xcheckbox', 'onchange' => 'calculateSelectedAmount()']) !!}</td>
@else
            <td></td>
@endif
            <td>{{ $payment->id }}</td>
            <td>
          <a href="{{ URL::to('customerinvoices/' . optional($payment->customerinvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerinvoice->document_reference ?? '' }}</a></td>
      <td>{{ abi_date_short(optional($payment->customerinvoice)->document_date) }}</td>
            <td>
          <a href="{{ URL::to('customers/' . optional(optional($payment->customerinvoice)->customer)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerinvoice->customer->name_regular ?? '' }}</a></td>
            <td @if ( !$payment->payment_date AND $payment->is_overdue ) ) class="danger" @endif>
                {{ abi_date_short($payment->due_date) }}</td>
            <td>{{ abi_date_short($payment->payment_date) }}</td>
            <td class="text-right">{{ $payment->as_money_amount('amount') }}

              <input name="pay_amount[{{ $payment->id }}]" id="pay_amount[{{ $payment->id }}]" class=" hide  selectedamount form-control input-sm" type="text" size="3" maxlength="5" style="min-width: 0; xwidth: auto; display: inline;" value="{{ $payment->as_priceable($payment->amount, $payment->currency) }}" onclick="this.select()" onkeyup="calculateSelectedAmount()">

      </td>

      <td>{{ optional($payment->paymenttype)->name }} 

@if( ($payment->payment_type_id == AbiConfiguration::getInt('DEF_CHEQUE_PAYMENT_TYPE')) && $payment->chequedetail )

              <a class="btn btn-xs btn-warning" href="{{ URL::to('cheques/' . $payment->chequedetail->cheque_id . '/edit' ) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-external-link"></i></a>

@endif
      </td>

            <td class="text-center button-pad">
                @if     ( $payment->status == 'pending' )
                    <span class="label label-info">
                @elseif ( $payment->status == 'bounced' )
                    <span class="label label-danger">
                @elseif ( $payment->status == 'paid' )
                    <span class="label label-success">
                @else
                    <span>
                @endif
                {{\App\Payment::getStatusName($payment->status)}}</span>

              @if ( $payment->status == 'paid' )
{{--                @if ( AbiConfiguration::isTrue('ENABLE_CRAZY_IVAN') ) --}}

                    <a href="{{ route('customervoucher.unpay', [$payment->id]) }}" class="btn btn-xs btn-danger" 
                    title="{{l('Undo Payment')}}" xstyle="margin-left: 22px;"><i class="fa fa-undo"></i></a>
               
{{--                @endif --}}
              @endif

            </td>


            <td class="text-right button-pad">
              @if ( ( $payment->status == 'paid' ) || ( $payment->status == 'bounced' ) )

                @else

                    <a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id . '/edit?back_route=' . urlencode('productionsheetvouchers/' . $productionSheet->id ) ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
 
      @if ($payment->bankorder && ($payment->bankorder->status == 'pending'))
      @else
                    <a class="btn btn-sm btn-blue" href="{{ URL::to('customervouchers/' . $payment->id  . '/pay?back_route=' . urlencode('productionsheetvouchers/' . $productionSheet->id ) ) }}" title="{{l('Make Payment')}}"><i class="fa fa-money"></i>
                    </a>
      @endif

                    @if(0 && $payment->amount==0.0)
 
      @if ($payment->bankorder && ($payment->bankorder->status != 'pending'))
      @else
                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                        href="{{ URL::to('customervouchers/' . $payment->id ) }}" 
                        data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ l('Customer Voucher', 'customervouchers') }} :: {{ l('Invoice') }}: {{ $payment->paymentable->document_reference }} . {{ l('Due Date') }}: {{ $payment->due_date }}" 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
      @endif

                    @endif

                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! $payments->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $payments->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>




@section('scripts') @parent 

<script type="text/javascript">

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery

$(function () {
    var $tblChkBox = $("#payment_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});

$("#payment_lines").on("change", function () {
    if (!$(this).prop("checked")) {
        $("#ckbCheckAll").prop("checked", false);
    }
});

// check box selection ENDS -->



$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });

   calculateSelectedAmount();

});



        function calculateSelectedAmount() {
            var total = 0;
            $('.xcheckbox:checked').each(function(index,value){

                total += parseFloat($(this).closest('tr').find('.selectedamount').val().replace(',', '.'));

            });

            $('#group_balance').html(currencyFormat{{ AbiContext::getContext()->currency->iso_code }}(total));
        }


        function currencyFormatUSD(num) {
          return '$' + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        // console.info(currencyFormat(2665)) // $2,665.00
        // console.info(currencyFormat(102665)) // $102,665.00


        function currencyFormatEUR(num) {
          return (
            num
              .toFixed(2) // always two decimal digits
              .replace('.', ',') // replace decimal point character with ,
              .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ' €'
          ) // use . as a separator
        }

        // console.info(currencyFormatDE(1234567.89)) // output 1.234.567,89 €



</script>

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#bulk_payment_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
</script>

@endsection

@section('styles')    @parent

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

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

@endsection
