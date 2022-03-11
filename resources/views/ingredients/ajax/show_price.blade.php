<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
                <th class="text-left">{{l('Reference')}}</th>
                <th class="text-left">{{l('Name')}}</th>
                <th class="text-left">{{l('Notes')}}</th>
                <th class="text-right">{{l('Stock')}}</th>
                <th class="text-right">{{l('On Order')}}</th>
                <th class="text-right">{{l('Allocated')}}</th>
                <th class="text-right">{{l('Available')}}</th>
                <th></th>
			</tr>
		</thead>
		<tbody>
			<tr>  
        <td class="text-left">
            @if ($product->combinations->count())
              <span class="label xlabel-info" style="border: 1px solid #cccccc"><a title=" {{l('Go to Product')}} " target="_blank" href="{{ URL::to('products').'/'.$product->id.'/edit' }}">{{l('Combinations')}}</a></span>
            @else
              <a title=" {{l('Add to Document', [], 'layouts')}} " onclick="add_product_to_document( {{ $product->id }}, 0 )" href="javascript:void(0);">{{$product->reference}}</a>
            @endif
        </td>
        <td class="text-left">
            <a title=" {{l('Go to Product')}} " target="_blank" href="{{ URL::to('products').'/'.$product->id.'/edit' }}">{{$product->name}}</a>
        </td>
				<td class="text-left">
                     {{$product->notes}}
                </td>
                <td class="text-right">{{$product->as_quantity('quantity_onhand')}}</td>
                <td class="text-right">{{$product->as_quantity('quantity_onorder')}}</td>
                <td class="text-right">{{$product->as_quantity('quantity_allocated')}}</td>
                <td class="text-right" id="quantity_available"><strong>{{$product->as_quantityable($product->quantity_available)}}</strong></td>
                <script type="text/javascript">
                	if ( parseFloat($("#quantity_available").text()) <= 0 ) $("#quantity_available").addClass('alert-danger');
                </script>
                <td>
                  @if (!$product->combinations->count())
                  <a title=" {{l('Add to Document', [], 'layouts')}} " onclick="add_product_to_document( {{ $product->id }}, 0 )" href="javascript:void(0);">
                		<button type="button" class="btn btn-xs btn-primary">
                			<i class="fa fa-shopping-basket"></i>
                		</button>
                	</a>
                  @endif
                </td>
            </tr>                          
		</tbody>
	</table>
</div>

<!-- Combination List -->

@if ($product->combinations->count())

<div class="panel panel-info" style="margin-bottom: 0px;">
  <div class="panel-heading">
    <h3 class="panel-title"><b>{{l('Combinations')}}</b></h3>
  </div>
</div>

<!-- Combination List -->
<div id="panel_combination_list">

    <div id="div_combinations">
       <div class="table-responsive">

    <table id="products" class="table table-hover">
        <thead>
            <tr>
          <th>{{l('ID', [], 'layouts')}}</th>
          <th>{{l('Reference')}}</th>
          <th>{{l('Options')}}</th>
          <th>{{l('Stock')}}</th>
          <th class="text-right">{{l('On Order')}}</th>
          <th class="text-right">{{l('Allocated')}}</th>
          <th class="text-right">{{l('Available')}}</th>
          <th class="text-center">{{l('Active', [], 'layouts')}}</th>
          <th class="text-right"> </th>
        </tr>
      </thead>
      <tbody>
      @foreach ($product->combinations as $combination)
      @php
          $combination->quantity_decimal_places = $product->quantity_decimal_places;
      @endphp
        <tr>
          <td>{{ $combination->id }}</td>
          <td>{{ $combination->reference }}</td>
          <td>
              {!! $combination->name() !!}
              {{-- json_encode( array_add( $combination, 'combination_name', $combination->name() ) )  --}}
          </td>
          <td>{{ $combination->as_quantity('quantity_onhand') }}</td>
          <td class="text-right">{{$combination->as_quantity('quantity_onorder')}}</td>
          <td class="text-right">{{$combination->as_quantity('quantity_allocated')}}</td>
          <td class="text-right"><strong>{{$combination->as_quantityable($combination->quantity_available)}}</strong></td>
          <td class="text-center">@if ($combination->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
               <td class="text-right">
                    
                  <a title=" {{l('Add to Document', [], 'layouts')}} " onclick="add_product_to_document( {{ $product->id }}, {{ $combination->id }} )" href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-primary">
                      <i class="fa fa-shopping-basket"></i>
                    </button>
                  </a>

                </td>
        </tr>
      @endforeach
        </tbody>
    </table>

       </div>
    </div>

</div>

@endif

<!-- Combination List ENDS -->



<div class="panel panel-info" style="margin-bottom: 0px;">
  <div class="panel-heading">
    <h3 class="panel-title"><b>{{l('Customer')}}</b>: {{ $customer->name_fiscal }}</a></h3>
  </div>
  <div class="panel-body">
    <b>{{l('Currency')}}</b>: {{ $currency->name }}<br />
    <b>{{l('Price List')}}</b>: 
                   @if ($customer->currentpricelist()) 
                        {{ $customer->currentpricelist()->name }}
                   @else {{ l('None') }} @endif</a>
  </div>
</div>

<div class="modal-body">
   <span id="detalle">
      <table class="table table-condensed">
        <thead>
          <tr>
            <th class="text-left">{{ l('Price') }}</th>
            <th class="text-left">{{ l('Cost Price') }}</th>
            <th class="text-left">{{ l('Margin') }} %</th>

            <th class="text-right">{{ l('Customer Price') }}</th>

            <th class="text-right">{{ l('Discount (%)') }}</th>

            <th class="text-right">{{ l('Margen Cliente') }} %</th>

            <th class="text-right">{{ l('Customer With Tax') }}</th>
          </tr>
        </thead>
        <tbody id="lineas_detalle">
          
          <tr>
            <td>{{$product->as_money('price')}}</td>
            <td>{{$product->as_price('cost_price')}}</td>
            <td>{{$product->as_percentable(\App\Models\Calculator::margin($product->cost_price, $product->price))}}</td>

            <td class="text-right">{{$product->as_moneyable($product->customer_price->getPrice(), $currency)}}<br />
            {{$product->as_moneyable($product->customer_price->convertToBaseCurrency()->getPrice())}}</td>

            <td class="text-right">{{$product->as_priceable($product->price-$product->customer_price->convertToBaseCurrency()->getPrice())}} ({{ $product->as_percentable(100.0*($product->price-$product->customer_price->convertToBaseCurrency()->getPrice())/$product->price) }}%)</td>

            <td class="text-right">{{$product->as_percentable( \App\Models\Calculator::margin($product->cost_price, $product->customer_price->convertToBaseCurrency()->getPrice() ))}}</td>

            <td class="text-right">{{$product->as_moneyable($product->customer_price->getPriceWithTax(), $currency)}}</td>
          </tr>
          
        </tbody>
      </table>
   </span>

   <br><br>
   
   <b>{{ l('Margin') }}</b>: 
   @if ( AbiConfiguration::get('MARGIN_METHOD') == 'CST' )  
      {{ l('Margin calculation is based on Cost Price', [], 'layouts') }}.
   @else
      {{ l('Margin calculation is based on Sales Price', [], 'layouts') }}.
   @endif
   <br>
</div>