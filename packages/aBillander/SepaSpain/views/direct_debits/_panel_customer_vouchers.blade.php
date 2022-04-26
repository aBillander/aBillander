

{!! Form::open( ['route' => ['customervouchers.payvouchers'], 'method' => 'POST', 'id' => 'form-payvouchers'] ) !!}

{{ Form::hidden('bank_order_id', $directdebit->id, array('id' => 'bank_order_id')) }}

<div class="panel-body" id="div_payments">
   <div class="table-responsive">

@if (optional($directdebit->vouchers)->count())

<table id="payments" class="table table-hover">
	<thead>
		<tr>
      <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Invoice')}}</th>
			<th>{{l('Customer')}}</th>
			<th>{{l('Subject')}}</th>
			<th>{{l('Due Date')}}</th>
			<th>{{l('Payment Date')}}</th>
			<th>{{l('Amount')}}</th>
      <th style="text-transform: none;">{{l('Payment Type', 'customervouchers')}}</th>
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody id="voucher_lines">
	@foreach ($directdebit->vouchers as $payment)
		<tr>

      @if ( $payment->status == 'pending' )
      <td class="text-center warning">{!! Form::checkbox('vouchers[]', $payment->id, false, ['class' => 'case checkbox']) !!}</td>
      @else
      <td> </td>
      @endif

			<td>{{ $payment->id }}</td>
			<td>
          <a href="{{ URL::to('customerinvoices/' . optional($payment->customerInvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerInvoice->document_reference or '' }}</a></td>
			<td>
          <a href="{{ URL::to('customers/' . optional(optional($payment->customerInvoice)->customer)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerInvoice->customer->name_regular or '' }}</a></td>
			<td>{{ $payment->name }}</td>
			<td @if ( !$payment->payment_date AND $payment->is_overdue ) ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<td>{{ abi_date_short($payment->payment_date) }}</td>
			<td>
            @if ( !$payment->paymentable->customer->bankaccount )
                <i class="fa fa-exclamation-triangle btn-xs alert-danger" title="{{ l('Not a valid Bank Account!', 'customervouchers') }}"></i>
            @endif



          {{ $payment->as_money_amount('amount') }}</td>

      <td>{{ optional($payment->paymenttype)->name }}</td>

            <td class="text-center">
            	@if     ( $payment->status == 'pending' )
            		<span class="label label-info">
            	@elseif ( $payment->status == 'bounced' )
            		<span class="label label-danger">
            	@elseif ( $payment->status == 'paid' )
            		<span class="label label-success">
            	@else
            		<span>
            	@endif
            	{{\App\Models\Payment::getStatusName($payment->status)}}</span></td>

			<td class="text-right button-pad">
              @if ( $directdebit->status == 'pending' )

                <a class="btn btn-sm btn-success xcustomer-voucher-setduedate" href="{{ URL::to('customervouchers/' . $payment->id . '/setduedate?back_route=' . urlencode('sepasp/directdebits/' . $directdebit->id)) }}" title="{{l('Change Due Date')}}"><i class="fa fa-calendar"></i></a>

                <a class="btn btn-sm btn-warning unlink-customer-voucher" href="{{ URL::to('customervouchers/' . $payment->id . '/unlink') }}" title="{{l('Unlink')}}" data-oid="{{ $payment->id }}" data-boid="{{ $payment->bank_order_id }}" data-oreference="{{ $payment->reference }}" onClick="return false;"><i class="fa fa-unlink"></i></a>


              @endif

    @if ( $directdebit->status == 'confirmed' )
              	<!-- a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id . '/edit' ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->

              @if ( $payment->status == 'pending' )
                <a class="btn btn-sm btn-warning unlink-customer-voucher" href="{{ URL::to('customervouchers/' . $payment->id . '/unlink') }}" title="{{l('Unlink')}}" data-oid="{{ $payment->id }}" data-boid="{{ $payment->bank_order_id }}" data-oreference="{{ $payment->reference }}" onClick="return false;"><i class="fa fa-unlink"></i></a>

                <a class="btn btn-sm btn-danger" href="{{ URL::to('customervouchers/' . $payment->id  . '/edit?action=bounce&back_route=' . urlencode('sepasp/directdebits/' . $directdebit->id) ) }}" title="{{l('Bounce', 'customervouchers')}}"><i class="fa fa-mail-reply-all"></i>
                  </a>

                <a class="btn btn-sm btn-blue xpay-customer-voucher" href="{{ URL::to('customervouchers/' . $payment->id  . '/pay?back_route=' . urlencode('sepasp/directdebits/' . $directdebit->id) ) }}" title="{{l('Make Payment', 'customervouchers')}}"><i class="fa fa-money"></i>
                  </a>
              @endif

              @if ( $payment->status == 'paid' )
                <a class="btn btn-xs btn-danger" href="{{ URL::to('customervouchers/' . $payment->id  . '/edit?action=bounce&back_route=' . urlencode('sepasp/directdebits/' . $directdebit->id) ) }}" title="{{l('Bounce', 'customervouchers')}}"><i class="fa fa-mail-reply-all"></i>
                  
              @endif

              @if ( $payment->status == 'bounced' )
            	@endif

    @endif

    @if ( $directdebit->status == 'closed' )

              @if ( $payment->status == 'paid' )
                <a class="btn btn-xs btn-danger" href="{{ URL::to('customervouchers/' . $payment->id  . '/edit?action=bounce&back_route=' . urlencode('sepasp/directdebits/' . $directdebit->id) ) }}" title="{{l('Bounce', 'customervouchers')}}"><i class="fa fa-mail-reply-all"></i>
                  </a>

              @endif

    @endif

			</td>
		</tr>
	@endforeach
	</tbody>
</table>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div><!-- div class="panel-body" -->

<div class="panel-footer">




<div class="text-right">

@if ( $directdebit->vouchers->where('status', 'pending')->count() )

  <button class=" btn xbtn btn-info create-production-order pull-left" title="{{l('Unlink selected Vouchers from Remittance')}}" onclick = "this.disabled=true;$('#form-payvouchers').attr('action', '{{ route( 'customervouchers.unlinkvouchers' )}}');$('#form-payvouchers').submit();return false;">
    <i class="fa fa-unlink"></i> &nbsp;{{l('Unlink Vouchers')}}
  </button>

               <strong class="{{ $errors->has('payment_type_id') ? 'text-danger' : '' }}">{{ l('Payment Type', 'customervouchers') }}</strong>: &nbsp;


               {!! Form::select('payment_type_id', ['' => l('-- None --', [], 'layouts')] + $payment_typeList, null, array('class' => 'xform-control', 'id' => 'payment_type_id',  'style' => 'xwidth:96px')) !!}



               <strong class="{{ $errors->has('payment_date') ? 'text-danger' : '' }}">{{ l('Payment Date', 'customervouchers') }}</strong>: &nbsp;


               {!! Form::text('payment_date_form', abi_date_short( \Carbon\Carbon::now() ), array('class' => 'xform-control', 'id' => 'payment_date_form',  'style' => 'width:96px', 'autocomplete' => 'off')) !!}
               {{-- !! $errors->first('payment_date', '<span class="help-block">:message</span>') !! --}}


  <!-- a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a -->
  <button class="btn xbtn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();" title="{{ l('Make Payment of selected Vouchers', 'customervouchers') }}">
     <i class="fa fa-money"></i>
     &nbsp; {{ l('Make Payment', 'customervouchers') }}
  </button>

@endif
</div>

</div>

{!! Form::close() !!}



{{--
@include('sepa_es::direct_debits._modal_pay_customer_voucher')
--}}

@include('sepa_es::direct_debits._modal_unlink_customer_voucher')


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {

    // Payment Type = "Remesa"
    $("#payment_type_id").val("{{ AbiConfiguration::getInt("DEF_SEPA_PAYMENT_TYPE") }}");

});

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery

$(function () {
    var $tblChkBox = $("#voucher_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});

$("#voucher_lines").on("change", function () {
    if (!$(this).prop("checked")) {
        $("#ckbCheckAll").prop("checked", false);
    }
});

// check box selection ENDS -->


</script>

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#payment_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

{{--
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

--}}
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
