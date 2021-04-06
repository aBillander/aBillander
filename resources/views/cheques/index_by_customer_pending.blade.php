

<div class="page-header">
    <!-- div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('customervouchers/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div -->
    <div class="pull-right" style="padding-top: 4px;">
    </div>
    <h2>
        {{ l('Customer Vouchers') }} <span class="lead well well-sm">

                  <a href="{{ URL::to('customers/' . $customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{{ $customer->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address', 'customerinvoices')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$customer->identification}}<br />
                                  {{ $customer->address->address1 }} {{ $customer->address->address2 }}<br />
                                  {{ $customer->address->postcode }} {{ $customer->address->city }}, {{ $customer->address->state->name }}<br />
                                  {{ $customer->address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
                 @if($customer->sales_equalization)
                  <span id="sales_equalization_badge" class="badge" title="{{l('Equalization Tax')}}"> RE </span>
                 @endif
                 </span>
                   &nbsp; 
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $customer->currency->name }}">{{ $customer->currency->iso_code }}</span>
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
    </h2>        
</div>




<div id="div_payments">
   <div class="table-responsive">

@if ($payments->count())
<table id="payments" class="table table-hover">
	<thead>
		<tr>
      <th class="text-center">{{-- !! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !! --}} 
              <a href="javascript:void(0);" data-toggle="popover" data-placement="right" 
                        data-content="{{ l('Select Voucher and Amount.') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
      </th>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Invoice')}}</th>
			<!-- th>{{l('Customer')}}</th -->
			<th>{{l('Subject')}}</th>
			<th>{{l('Due Date')}}</th>
			<!-- th>{{l('Payment Date')}}</th -->
			<th class="text-right">{{l('Amount')}}</th>
      <th style="text-transform: none;">{{l('Payment Type', 'customervouchers')}}</th>
      <th style="text-transform: none;">{{l('Auto Direct Debit', 'customervouchers')}}</th>
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th class="text-center"><div id="balance" class="alert-warning">{{l('Make Payment')}}</div></th>
		</tr>
	</thead>
	<tbody id="document_lines">
	@foreach ($payments as $payment)
		<tr>
      <td class="text-center warning">{!! Form::checkbox('document_group[]', $payment->id, false, ['class' => 'case xcheckbox', 'onchange' => 'calculateSelectedAmount()']) !!}</td>

			<td>{{ $payment->id }}</td>
			<td>
          <a href="{{ URL::to('customerinvoices/' . optional($payment->customerInvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerInvoice->document_reference or '' }}</a></td>
			<!-- td>
          <a href="{{ URL::to('customers/' . optional(optional($payment->customerInvoice)->customer)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->customerInvoice->customer->name_regular or '' }}</a></td -->
			<td>{{ $payment->name }}</td>
			<td @if ( !$payment->payment_date AND $payment->is_overdue ) ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<!-- td>{{ abi_date_short($payment->payment_date) }}</td -->
			<td class="text-right">{{ $payment->as_money_amount('amount') }}</td>

      <td>{{ optional($payment->paymenttype)->name }}</td>

      <td class="text-center">
        @if ($payment->auto_direct_debit) 
          @if ($payment->bankorder)
            <a class="btn btn-xs btn-grey" href={{ route('sepasp.directdebits.show', $payment->bankorder->id) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-bank"></i>
                      <span xclass="label label-default">{{ $payment->bankorder->document_reference }}</span>
                    </a>
          @else
            <i class="fa fa-check-square xbtn-xs xalert-success" style="color: #38b44a;"></i>
            @if ( !$payment->paymentable->customer->bankaccount )
                <i class="fa fa-exclamation-triangle btn-xs alert-danger" title="{{ l('Not a valid Bank Account!') }}"></i>
            @endif
          @endif
        @else 
          <i class="fa fa-square-o" style="color: #df382c;"></i>
        @endif
      </td>

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
            	{{\App\Payment::getStatusName($payment->status)}}</span></td>


      <td class="text-center">
          @if ($payment->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $payment->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif
      </td>


			<td class="text-right">
              <input name="pay_amount[{{ $payment->id }}]" id="pay_amount[{{ $payment->id }}]" class=" selectedamount form-control input-sm" type="text" size="3" maxlength="7" style="min-width: 0; xwidth: auto; display: inline;" value="{{ $payment->as_priceable($payment->amount, $payment->currency) }}" onclick="this.select()" onkeyup="calculateSelectedAmount()">
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
</div>



@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
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
