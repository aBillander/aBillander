

	 <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h4 class="modal-title"> 

<button type="button" class="btn btn-sm alert-info" data-dismiss="modal">{{ $customer->name_regular }}</button>

	    {{ l('Product consumption') }} :: <label class="label label-success">{{ $product->reference }}</label> {{ $product->name }} ({{ $product->id }})</h4>
	 </div>

	 <div class="modal-body">

       <div class="table-responsive">

<table id="liness" name="liness" class="table table-hover">
	<thead>
		<tr>
			<th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th style="text-transform: none;">{{l('Date')}}</th>
			<th style="text-transform: none;">{{l('Document')}}</th>
			<th style="text-transform: none;">{{l('Quantity')}}</th>

			<th style="text-transform: none;">{{l('Price')}}</th>
			<th style="text-transform: none;">{{l('Customer Price')}}</th>
			<th style="text-transform: none;">{{l('Customer Final Price')}}</th>
		</tr>
	</thead>
	<tbody>
@if ($lines->count())


	@foreach ($lines as $line)
		<tr>
			<td>{{ $line->id }}</td>
			<td>{{ abi_date_short( $line->document->document_date ) }}</td>
			<td>
        		<a href="{{ route($line->route.'.edit', [$line->document->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_new">
						@if ( $line->document->document_reference )
		                	{{ $line->document->document_reference}}
		                @else
		                	<span class="btn btn-xs btn-grey">{{ l('Draft', 'layouts') }}</span>
		                @endif
        		</a>
        	</td>
			<td>{{ $line->as_quantity('quantity') }}</td>

			<td>{{ $line->as_price('unit_price') }}</td>
			<td>{{ $line->as_price('unit_customer_price') }}</td>
			<td>{{ $line->as_price('unit_customer_final_price') }}</td>
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

	   <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Back', [], 'layouts')}}</button>

	   <!-- button type="submit" class="btn btn-success" name="modal_edit_document_line_productSubmit" id="modal_edit_document_line_productSubmit">
	    <i class="fa fa-thumbs-up"></i>
	    &nbsp; {{l('Update', [], 'layouts')}}</button -->

	</div>
