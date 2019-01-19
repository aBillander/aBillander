

	 <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h4 class="modal-title">{{ l('Show Payments') }} :: <label class="label label-default">{{ $document->document_reference }}</label></h4>
	 </div>

	 <div class="modal-body">

       <div class="table-responsive">

<table id="payments" name="payments" class="table table-hover">
	<thead>
		<tr>
			<th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th style="text-transform: none;">{{l('Subject')}}</th>
			<th style="text-transform: none;">{{l('Due Date')}}</th>
			<th style="text-transform: none;">{{l('Payment Date')}}</th>
			<th style="text-transform: none;">{{l('Amount')}}</th>
            <th style="text-transform: none;" class="text-center">{{l('Status', [], 'layouts')}}</th>
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
			<td>{{ $payment->name }}</td>
			<td @if( !$payment->payment_date AND ( \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $payment->due_date) < \Carbon\Carbon::now() ) ) class="danger" @endif>
				{{ $payment->due_date }}</td>
			<td>{{ $payment->payment_date }}</td>
			<td>{{ abi_money_amount($payment->amount, $document->currency) }}</td>
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
            	{{l( $payment->status, [], 'appmultilang' )}}</span></td>
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



	 </div><!-- div class="modal-body" ENDS -->

	<div class="modal-footer">

	   <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>

	   <!-- button type="submit" class="btn btn-success" name="modal_edit_document_line_productSubmit" id="modal_edit_document_line_productSubmit">
	    <i class="fa fa-thumbs-up"></i>
	    &nbsp; {{l('Update', [], 'layouts')}}</button -->

	</div>
