
<!-- div xclass="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

    </div>
    <h2>
            <span style="color: #dd4814;">{{ l('Products') }}</span>
    </h2>        
</div -->


<div id="div_products">
   <div class="table-responsive">

@if ($products->count())
<table id="products" class="table table-hover">
    <thead>
        <tr>
			<th>{{l('ID', [], 'layouts')}}</th>
      <th>{{ l('Work Center') }}</th>
      <th>{{-- l('Procurement type') --}}</th>
      <th>{{ l('Product Name') }}</th>
            <th>{{ l('Main Supplier') }}</th>
			<!-- th>{{ l('Measure Unit') }}</th -->
            <th>{{ l('Stock') }}</th>
            <th>{{ l('Allocated') }}</th>
            <th>{{ l('On Order') }}</th>
            <th>{{ l('Available') }}

                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"data-html="true"  
                              data-content="{{ l('Available Stock: <br />[Physical Stock] <br />+ [Orders to Suppliers] <br />- [Customer Orders] <br />+ [Not finished Production Orders] <br />- [Production Orders Reserves]') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>

            </th>
            <th>{{ l('Re-Order Point') }}</th>
            <th>{{ l('Maximum stock') }}</th>
            <!-- th>{{ l('Manufacturing Batch Size') }}</th -->
            <th>{{ l('Suggested Quantity') }}</th>
            <!-- th>{{ l('Cost Price') }}</th -->
            <!-- th>{{ l('Customer Price') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') ?
                                    l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                    l('Prices are entered exclusive of tax', [], 'appmultilang') }}">
                    <i class="fa fa-question-circle abi-help"></i>
                 </a></th -->
            <!-- th>{{ l('Tax') }}</th -->
            <!-- th>{{ l('Tax') }} (%)</th -->
            <!-- th>{{ l('Category') }}</th -->
            <!-- th>{{ l('Quantity decimals') }}</th>
            <th>{{ l('Manufacturing Batch Size') }}</th -->
			<th class="text-right"> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($products as $product)
		<tr>
			<td>{{ $product->id }}</td>
			<td title="{{ $product->work_center_id }}">
                <span class="text-success">{{ $work_centerList[ $product->work_center_id ] ?? '-' }}</span>
            </td>

      <td>{{ \App\Models\Product::getProcurementTypeName($product->procurement_type) }}<br />
        <span class="text-info">{{ \App\Models\Product::getMrpTypeName($product->mrp_type) }}</span>

      </td>

      <td>[<a target="_blank" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}">{{ $product->reference }}</a>] {{ $product->name }}

                <a class="btn btn-xs alert-warning  hide " target="_blank" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}"><i class="fa fa-external-link"></i></a>
      </td>
      <td>
@if ( ($product->procurement_type == 'purchase') && ($product->main_supplier_id > 0) )
        <a target="_blank" href="{{ route('suppliers.edit', $product->main_supplier_id ) }}" title="{{l('Go to', [], 'layouts')}}">
        {{-- optional($product->mainsupplier)->name_fiscal --}}
        {{ $supplierList[ $product->main_supplier_id ] ?? '-' }}
        </a>
@else
        <a>
        -
        </a>
@endif
      </td>
			<!-- td>{{ $product->measureunit->name }}</td -->
            <td>{{ $product->as_quantity('quantity_onhand') }}</td>
            <td>{{ $product->as_quantity('quantity_allocated') }}</td>
            <td>{{ $product->as_quantity('quantity_onorder')  }}</td>
            <td>{{ $product->as_quantity('quantity_onhand') - $product->as_quantity('quantity_allocated') + $product->as_quantity('quantity_onorder') }}</td>
            <td>{{ $product->as_quantity('reorder_point') }}</td>
            <td>{{ $product->as_quantity('maximum_stock') }}</td>
            <!--td>{{ $product->as_quantity('manufacturing_batch_size') }}</td -->
            <td>{{ $product->as_quantityable($product->quantity_reorder_suggested) }}</td>
            <!-- td>{{ $product->as_price('cost_price') }}</td -->
            <!-- td>{{ $product->displayPrice }}</td -->
            <!-- td>{ { $product->tax->name } }</td -->
            <!-- td>{ { $product->as_percentable($product->tax->percent) } }</td -->
            <!-- td>@ if (isset($ product->category)) {{-- $product->category->name --}} @ else - @ endif</td -->
            <!-- td>{{ $product->quantity_decimal_places }}</td>
            <td>{{ $product->manufacturing_batch_size }}</td -->

           <td class="text-right button-pad">

                <a class="btn btn-sm btn-info" target="_blank" href="{{ route('chart.product.stock.monthly', ['product_id' => $product->id]) }}" title="{{l('View Chart', [], 'layouts')}}"><i class="fa fa-bar-chart-o"></i></a>

                <a class="btn btn-sm btn-warning  hide " target="_blank" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
            </td>
		</tr>
	@endforeach
    </tbody>
</table>
{!! $products->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->total() ], 'layouts')}} </span></li></ul>
@else
      @if( Request::has('search_query') AND (Request::input('search_query')==0) )
      @else
            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{l('No records found', [], 'layouts')}}
            </div>
      @endif
@endif

   </div>
</div>
