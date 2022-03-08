
    <!-- div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Lines') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} - - >
        </h3>        
    </div -->

    <div id="div_document_lines">
       <div class="table-responsive">

<table id="downpayments" name="downpayments" class="table table-hover">
	<thead>
		<tr>
			<th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th style="text-transform: none;">{{l('Subject')}}</th>
			<th style="text-transform: none;">{{l('Due Date')}}</th>
			<th style="text-transform: none;">{{l('Amount')}}</th>
            <th style="text-transform: none;" class="text-center">{{l('Status', [], 'layouts')}}</th>
			<th>
				
                <a href="{{ route('supplierorder.create.downpayment', $document->id) }}" class="btn btn-sm btn-success create-chequedetail pull-right"
                    title="{{l('Add New Down Payment')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
			</th>
		</tr>
	</thead>
	<tbody>
@if ($document->downpayments->count())


	@foreach ($document->downpayments->sortBy(function ($item, $key) {
											    return $item->getAttributes()['due_date'];
											}) as $downpayment)
{{-- https://stackoverflow.com/questions/17543843/skip-model-accessor --}}
		<tr>
			<td>{{ $downpayment->id }}</td>
			<td>{{ $downpayment->name }}</td>
			<td>
				{{ abi_date_short($downpayment->due_date) }}</td>
			<td>{{ abi_money_amount($downpayment->amount, $document->currency) }}</td>
            <td class="text-center">
            	@if     ( $downpayment->status == 'pending' )
            		<span class="label label-info">
            	@elseif ( $downpayment->status == 'bounced' )
            		<span class="label label-danger">
            	@elseif ( $downpayment->status == 'applied' )
            		<span class="label label-success">
            	@else
            		<span>
            	@endif
            	{{ $downpayment->status_name }}</span></td>

			<td class="text-right">
                @if ( $downpayment->status == 'applied' )
                	<!-- a class="btn btn-sm btn-danger" href="{{ URL::to('customervouchers/' . $downpayment->id  . '/edit?back_route=' . urlencode('customerinvoices/' . $document->id . '#downpayments') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
            	@else
                	<a class="btn btn-sm btn-warning" href="{{ URL::to('supplierdownpayments/' . $downpayment->id  . '/edit') }}" title="{{l('Edit', [], 'layouts')}}" target="_blank"><i class="fa fa-pencil"></i></a>
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
