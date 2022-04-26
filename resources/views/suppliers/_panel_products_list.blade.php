

       <div class="table-responsive">

<table id="productss" name="productss" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Product')}}</th>
			<th>{{l('Stock', 'products')}}</th>

			<th>{{l('Measure Unit', 'products')}}</th>
			<th>{{l('Purchase Measure Unit', 'products')}}</th>
			<th>{{l('Last Purchase Price', 'products')}}</th>
			<th>{{l('Reorder point', 'products')}}</th>

@if ( AbiConfiguration::isTrue('ENABLE_LOTS') )

			<th class="text-center">{{l('Lot tracking?', 'products')}}</th>
@endif
			<th class="text-center">{{l('Active', [], 'layouts')}}</th>
		</tr>
	</thead>
	<tbody>
@if ($products->count())


	@foreach ($products as $product)
		<tr>
			<td>{{ $product->id }}</td>
			<td>
				[<a href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_product">{{ $product->reference }}</a>] {{ $product->name }}
			</td>
			<td>{{ $product->as_quantity('quantity_onhand') }}</td>

			<td>{{ $product->measureunit->name }}</td>
			<td>{{ optional($product->purchasemeasureunit)->name }}</td>
			<td>{{ $product->as_price('last_purchase_price') }}</td>
			<td>{{ $product->as_quantity('reorder_point') }}</td>
@if ( AbiConfiguration::isTrue('ENABLE_LOTS') )
			<td class="text-center">@if ($product->lot_tracking) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
@endif
			<td class="text-center">@if ($product->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
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


<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->count() ], 'layouts')}} </span></li></ul>

<ul class=" hide pagination" style="float:right;">
	<li xclass="active" style="float:right;">
	<span style="color:#333333;border-color:#ffffff"> 
		<div class="input-group">
			<span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Show records:', 'layouts')}}</span>
			<input id="items_per_page_products" name="items_per_page_products" class="form-control input-sm items_per_page_products" style="width: 50px !important;" type="text" value="{{ $items_per_page_products }}" onclick="this.select()">
		    <span class="input-group-btn">
		      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getSupplierProducts(); return false;"><i class="fa fa-refresh"></i></button>
		    </span>
  		</div>
  	</span>
	</li>
</ul>
