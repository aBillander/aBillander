

       <div class="table-responsive">

<table id="liness" name="liness" class="table table-hover">
	<thead>
		<tr>
			<th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th style="text-transform: none;">{{l('Date')}}</th>
			<th style="text-transform: none;">{{l('Document')}}</th>
			<th style="text-transform: none;">{{l('Product')}}</th>
			<th style="text-transform: none;">{{l('Quantity')}}</th>

			<th style="text-transform: none;">{{l('Price', 'products')}}</th>
			<th style="text-transform: none;">{{l('Customer Price', 'products')}}</th>
			<th style="text-transform: none;">{{l('Customer Final Price', 'products')}}</th>
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
			<td>
				[<a href="{{ URL::to('products/' . $line->product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}">{{ $line->product->reference }}</a>] {{ $line->product->name }}</td>
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


<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $lines->count() ], 'layouts')}} </span></li></ul>

<ul class="pagination" style="float:right;">
	<li xclass="active" style="float:right;">
	<span style="color:#333333;border-color:#ffffff"> 
		<div class="input-group">
			<span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Show records:', 'layouts')}}</span>
			<input id="items_per_page_products" name="items_per_page_products" class="form-control input-sm items_per_page_products" style="width: 50px !important;" type="text" value="{{ $items_per_page_products }}" onclick="this.select()">
		    <span class="input-group-btn">
		      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getCustomerProducts(); return false;"><i class="fa fa-refresh"></i></button>
		    </span>
  		</div>
  	</span>
	</li>
</ul>
