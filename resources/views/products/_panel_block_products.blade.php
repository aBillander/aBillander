
<div xclass="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

    </div>
    <h2>
        @if ( count($breadcrumb) )
           <a class="btn btn-sm btn-primary" href="{{ route('products.index') }}" title="{{l('Home', 'layouts')}}"><i class="fa fa-home"></i></a> 
           <span style="color: #cccccc;">/</span> 
          @foreach ($breadcrumb as $val)
            <span style="color: #dd4814;">{{ $val->name }}</span> <span style="color: #cccccc;">/</span> 
          @endforeach
        @else
            <span style="color: #dd4814;">{{ l('Products') }}</span>
        @endif
    </h2>        
</div>


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
			<!-- th>{{ l('Measure Unit') }}</th>
            <th>{{ l('Stock') }}</th>
            <th>{{ l('Cost Price') }}</th -->
            <th>{{ l('Customer Price') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ?
                                    l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                    l('Prices are entered exclusive of tax', [], 'appmultilang') }}">
                    <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
            <!-- th>{{ l('Tax') }}</th -->
            <!-- th>{{ l('Tax') }} (%)</th -->
            <!-- th>{{ l('Category') }}</th -->
            <!-- th>{{ l('Quantity decimals') }}</th>
            <th>{{ l('Manufacturing Batch Size') }}</th -->
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th class="text-right"> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($products as $product)
		<tr>
			<td>{{ $product->id }}</td>
			<td title="{{ $product->id }}">@if ($product->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else {{ $product->reference }}
                @endif</td>
      <td>{{ $product->procurement_type }}</td>

      <td>
@php
  $img = $product->getFeaturedImage();
@endphp
@if ($img)
              <a class="view-image" data-html="false" data-toggle="modal" 
                     href="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-large_default' . '.' . $img->extension ) }}"
                     data-content="{{l('You are going to view a record. Are you sure?')}}" 
                     data-title="{{ l('Product Images') }} :: {{ $product->name }} " 
                     data-caption="({{$img->id}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('View Image')}}">

                      <img src="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">
              </a>
@endif
      </td>

      <td>{{ $product->name }}</td>
			<!-- td>{{ $product->measureunit->name }}</td>
            <td>{{ $product->as_quantity('quantity_onhand') }}</td>
            <td>{{ $product->as_price('cost_price') }}</td -->
            <td>{{ $product->displayPrice }}</td>
            <!-- td>{{ $product->tax->name }}</td -->
            <!-- td>{{ $product->as_percentable($product->tax->percent) }}</td -->
            <!-- td>@if (isset($product->category)) {{-- $product->category->name --}} @else - @endif</td -->
            <!-- td>{{ $product->quantity_decimal_places }}</td>
            <td>{{ $product->manufacturing_batch_size }}</td -->
            <td class="text-center">
                @if ($product->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $product->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
			<td class="text-center">@if ($product->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
           <td class="text-right button-pad">
                @if (  is_null($product->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('products/' . $product->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Products') }} :: ({{$product->id}}) {{{ $product->name }}}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('products/' . $product->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('products/' . $product->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
		</tr>
	@endforeach
    </tbody>
</table>
{!! $products->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>
