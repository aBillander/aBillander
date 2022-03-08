
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
			<td>{{ $payment->name }}</td>
			<td @if( !$payment->payment_date AND ( \Carbon\Carbon::createFromFormat( AbiContext::getContext()->language->date_format_lite, $payment->due_date) < \Carbon\Carbon::now() ) ) class="danger" @endif>
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

			<td class="text-right">
                @if ( $payment->status == 'paid' )
                	<!-- a class="btn btn-sm btn-danger" href="{{ URL::to('customervouchers/' . $payment->id  . '/edit?back_route=' . urlencode('customerinvoices/' . $document->id . '#payments') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
            	@else
                	<a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id  . '/edit?back_route=' . urlencode('customerinvoices/' . $document->id . '#payments') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
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
