


<div class="panel-body" id="div_payments">
   <div class="table-responsive">

@if (optional($directdebit->vouchers)->count())
<table id="payments" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Invoice')}}</th>
			<th>{{l('Customer')}}</th>
			<th>{{l('Subject')}}</th>
			<th>{{l('Due Date')}}</th>
			<th>{{l('Payment Date')}}</th>
			<th>{{l('Amount')}}</th>
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($directdebit->vouchers as $payment)
		<tr>
			<td>{{ $payment->id }}</td>
			<td>{{ $payment->customerInvoice->document_reference or '' }}</td>
			<td>{{ $payment->customerInvoice->customer->name_regular or '' }}</td>
			<td>{{ $payment->name }}</td>
			<td @if ( !$payment->payment_date AND $payment->is_overdue ) ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<td>{{ abi_date_short($payment->payment_date) }}</td>
			<td>{{ $payment->as_money_amount('amount') }}</td>

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

			<td class="text-right">
                @if ( $payment->status == 'paid' )

            	@else

                <a class="btn btn-sm btn-danger unlink-customer-voucher" href="{{ URL::to('customerorders/' . $payment->id . '/unlink') }}" title="{{l('Unlink')}}" data-oid="{{ $payment->id }}" data-oreference="{{ $payment->reference }}" onClick="return false;"><i class="fa fa-unlink"></i></a>



                	<!-- a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id . '/edit' ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->

	                <a class="btn btn-sm btn-blue xpay-customer-voucher" href="{{ URL::to('customervouchers/' . $payment->id  . '/pay' ) }}" title="{{l('Make Payment', 'customervouchers')}}"><i class="fa fa-money"></i>
	                </a>

	                @if($payment->amount==0.0)
	                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
	                    href="{{ URL::to('customervouchers/' . $payment->id ) }}" 
	                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
	                    data-title="{{ l('Customer Voucher', 'customervouchers') }} :: {{ l('Invoice') }}: {{ $payment->paymentable->document_reference }} . {{ l('Due Date') }}: {{ $payment->due_date }}" 
	                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
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

<div class="panel-footer text-right">
  <!-- a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a -->
  <!-- button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-plus"></i>
     &nbsp; {{ l('Add Production Order') }}
  </button -->

  <a class="btn xbtn btn-info create-production-order" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-money"></i> &nbsp;{{l('Set as Paid')}}</a>
</div>

{{--
@include('sepa_es::direct_debits._modal_pay_customer_voucher')

@include('sepa_es::direct_debits._modal_unlink_customer_voucher')
--}}


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {

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
