
    <!-- div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Lines') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} - - >
        </h3>        
    </div -->

    <div id="div_document_lines">
       <div class="table-responsive">

<table id="payments" name="payments" class="table table-hover">
	<thead>
		<tr>
			<th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th style="text-transform: none;">{{l('Subject')}}</th>
			<th style="text-transform: none;">{{l('Due Date')}}</th>
			<th style="text-transform: none;">{{l('Payment Date')}}</th>
			<th style="text-transform: none;">{{l('Amount')}}</th>
			<th style="text-transform: none;">{{l('Payment Type', 'suppliervouchers')}}</th>
			<!-- th style="text-transform: none;">{{l('Auto Direct Debit', 'suppliervouchers')}}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Include in automatic payment remittances', 'suppliervouchers') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
        	</th -->
            <th style="text-transform: none;" class="text-center">{{l('Status', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
@if ($document->payments->count())


	@foreach ($document->payments->sortBy(function ($item, $key) {
											    return $item->getAttributes()['due_date'];
											}) as $payment)
{{-- https://stackoverflow.com/questions/17543843/skip-model-accessor --}}
		<tr>
			<td>{{ $payment->id }}</td>
			<td>

@if( $payment->is_down_payment)
        <a href="{{ URL::to('supplierdownpayments/' . optional(optional($payment->downpaymentdetail)->downpayment)->id . '/edit') }}" class="btn btn-xs alert-danger" title="{{l('Down Payment') }}" target="_blank">&nbsp;<i class="fa fa-money"></i>&nbsp;</a>
@endif

				{{ $payment->name }}</td>
			<td @if ( !$payment->payment_date AND $payment->is_overdue ) class="danger" @endif>
				{{ abi_date_short($payment->due_date) }}</td>
			<td>{{ abi_date_short($payment->payment_date) }}</td>
			<td>{{ abi_money_amount($payment->amount, $document->currency) }}</td>

			<td>{{ optional($payment->paymenttype)->name }}</td>

			<!-- td class="text-center">
				@if ($payment->auto_direct_debit) 
					@if ($payment->bankorder)
						<a class="btn btn-xs btn-grey" href={{ route('sepasp.directdebits.show', $payment->bankorder->id) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-bank"></i>
		                	<span xclass="label label-default">{{ $payment->bankorder->document_reference }}</span>
		                </a>
                	@else
						<i class="fa fa-check-square" style="color: #38b44a;"></i>
					@endif
				@else 
					<i class="fa fa-square-o" style="color: #df382c;"></i>
				@endif
			</td -->

            <td class="text-center">
            	@if     ( $payment->status == 'pending' )
            		<span class="label label-info">
            	@elseif ( $payment->status == 'bounced' )
            		<span class="label label-danger">
            	@elseif ( $payment->status == 'paid' )
            		<span class="label label-success">
            	@elseif ( $payment->status == 'uncollectible' )
                	<span class="label alert-danger">
            	@else
            		<span class="label">
            	@endif
            	{{l( $payment->status, [], 'appmultilang' )}}</span></td>

			<td class="text-right">
                @if ( $payment->status == 'paid' )
                	<!-- a class="btn btn-sm btn-danger" href="{{ URL::to('suppliervouchers/' . $payment->id  . '/edit?back_route=' . urlencode('supplierinvoices/' . $document->id . '#payments') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
            	@else

                @if ( $payment->status == 'bounced' )

            	@else

                	<a class="btn btn-sm btn-warning" href="{{ URL::to('suppliervouchers/' . $payment->id  . '/edit?back_route=' . urlencode('supplierinvoices/' . $document->id . '/edit#payments') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

	                <a class="btn btn-sm btn-blue" href="{{ URL::to('suppliervouchers/' . $payment->id  . '/pay?back_route=' . urlencode('supplierinvoices/' . $document->id . '/edit#payments') ) }}" title="{{l('Make Payment', 'suppliervouchers')}}"><i class="fa fa-money"></i>
	                </a>

	                @if($payment->amount==0.0)
	                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
	                    href="{{ URL::to('suppliervouchers/' . $payment->id ) }}" 
	                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
	                    data-title="{{ l('Supplier Voucher', 'suppliervouchers') }} :: {{ l('Invoice') }}: {{ $payment->paymentable->document_reference }} . {{ l('Due Date') }}: {{ $payment->due_date }}" 
	                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
	                @endif

            	@endif
            	@endif
			</td>
		</tr>
	@endforeach

@else
    <tr><td colspan="10">
	<div class="alert alert-warning alert-block">
	    <i class="fa fa-warning"></i>
	    {{l('No records found', [], 'layouts')}}
	</div>
    </td>
    <td></td></tr>
@endif

	</tbody>
</table>



		</div>
	</div>


{{-- ******************************************************************************* --}}


<div id="msg-success-update" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-update-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

@if ($downpayments->count())
<div id="panel_document_downpayments" class="">
  
    @include($view_path.'._panel_document_down_payments')

</div>
@endif
