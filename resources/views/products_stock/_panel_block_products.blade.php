
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
      <th>{{ l('Reference') }}</th>
      <th>{{-- l('Procurement type') --}}</th>
      <th colspan="2">{{ l('Product Name') }}</th>
            <th>{{ l('Main Supplier') }}</th>
			<!-- th>{{ l('Measure Unit') }}</th -->
            <th>{{ l('Stock Control?') }}</th>
            <th>{{ l('Stock') }}</th>
{{--
            <th>{{ l('Allocated') }}</th>
            <th>{{ l('On Order') }}</th>
            <th>{{ l('Available') }}</th>
--}}
            <th>{{ l('Re-Order Point') }}</th>
            <th>{{ l('Maximum stock') }}</th>
            <!-- th>{{ l('Manufacturing Batch Size') }}</th -->
{{--
            <th>{{ l('Suggested Quantity') }}</th>
--}}
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
			<td title="{{ $product->id }}">@if ($product->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else <a target="_blank" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}">{{ $product->reference }}</a>
                @endif</td>

      <td>{{ \App\Models\Product::getProcurementTypeName($product->procurement_type) }}<br />
        <span class="text-info">{{ \App\Models\Product::getMrpTypeName($product->mrp_type) }}</span>

      </td>

      <td>
{{--
@php
  $img = $product->getFeaturedImage();
@endphp
@if ($img)
              <a class="view-image" data-html="false" data-toggle="modal" 
                     href="{{ URL::to( \App\Models\Image::pathProducts() . $img->getImageFolder() . $img->filename . '-large_default' . '.' . $img->extension ) }}"
                     data-content="{{l('You are going to view a record. Are you sure?')}}" 
                     data-title="{{ l('Product Images') }} :: {{ $product->name }} " 
                     data-caption="({{$img->filename}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('View Image')}}">

                      <img src="{{ URL::to( \App\Models\Image::pathProducts() . $img->getImageFolder() . $img->filename . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">
              </a>
@endif
--}}
      </td>

      <td>{{ $product->name }}</td>
      <td>
        <a target="_blank" href="{{ route('suppliers.edit', optional($product->mainsupplier)->id ) }}" title="{{l('Go to', [], 'layouts')}}">
        {{ optional($product->mainsupplier)->name_fiscal }}
        </a>
      </td>
            
            <td class="text-center">@if ($product->stock_control) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<!-- td>{{ $product->measureunit->name }}</td -->
            <td>{{ $product->as_quantity('quantity_onhand') }}</td>
{{--
            <td>{{ $product->as_quantity('quantity_allocated') }}</td>
            <td>{{ $product->as_quantity('quantity_onorder')  }}</td>
            <td>{{ $product->as_quantity('quantity_onhand') - $product->as_quantity('quantity_allocated') + $product->as_quantity('quantity_onorder') }}</td>
--}}
            <td>{{ $product->as_quantity('reorder_point') }}</td>
            <td>{{ $product->as_quantity('maximum_stock') }}</td>
            <!--td>{{ $product->as_quantity('manufacturing_batch_size') }}</td -->
{{--
            <td>{{ $product->as_quantityable($product->quantity_reorder_suggested) }}</td>
--}}
            <!-- td>{{ $product->as_price('cost_price') }}</td -->
            <!-- td>{{ $product->displayPrice }}</td -->
            <!-- td>{ { $product->tax->name } }</td -->
            <!-- td>{ { $product->as_percentable($product->tax->percent) } }</td -->
            <!-- td>@ if (isset($ product->category)) {{-- $product->category->name --}} @ else - @ endif</td -->
            <!-- td>{{ $product->quantity_decimal_places }}</td>
            <td>{{ $product->manufacturing_batch_size }}</td -->

           <td class="text-right button-pad">

                <a class="btn btn-sm btn-info" target="_blank" href="{{ route('chart.product.stock.monthly', ['product_id' => $product->id]) }}" title="{{l('View', [], 'layouts')}}"><i class="fa fa-bar-chart-o"></i></a>

                <a class="btn btn-sm btn-warning" target="_blank" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
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
