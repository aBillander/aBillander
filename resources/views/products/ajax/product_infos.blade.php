
<div id="xpanel_sales"> 


<!-- Price List -->

<div id="panel_sales_detail" style="padding-left: 15px; padding-right: 15px; padding-bottom: 20px;">

    <div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Price Lists') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $product->name }} -->
        </h3>        
    </div>

    <div id="div_aBook">
       <div class="table-responsive">

    <table id="aBook" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{l('Price List Name')}}</th>
                <th class="text-left">{{l('Currency')}}</th>
                <th class="text-left">{{l('Sales Price')}}</th>
                <th class="text-left">{{l('Discount (%)')}}</th>
                <th class="text-left">{{l('Margin (%)')}}</th>
                <th class="text-left">{{l('Price with Tax')}} </th>
                <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody>

            <tr style="color: #3a87ad; background-color: #d9edf7;">
                <td> </td>
                <td>{{ l('Base Price') }}<br />
                    {{ l('Cost Price') }}: {{ $product->as_price('cost_price') }}
                </td>
                <td>{{ AbiContext::getContext()->currency->name }}</td>
                <td>{{ $product->as_price('price') }}</td>
                <td> - </td>
                <td>{{ $product->as_percentable( \App\Helpers\Calculator::margin( $product->cost_price, $product->price ) ) }}</td>
                <td>{{ $product->as_priceable( $product->price*(1.0+($product->tax->percent/100.0)) ) }}</td>
                <td class="text-right"> </td>
            </tr>
            
    @if ($pricelists->count())

            @foreach ($pricelists as $pricelist)
                @php 
                      $theprice = $product->getPriceByList($pricelist);
                      $line_price = ( ( ($pricelist->type == 'price') AND $pricelist->price_is_tax_inc ) 
                              ? $theprice->amount/(1.0+($product->tax->percent/100.0))
                              : $theprice->amount 
                                    ); 
                @endphp
            <tr>
                <td>{{ $pricelist->id }}</td>
                <td>{{ $pricelist->name }}<br />
                    <span class="label label-success">{{ $pricelist->getType() }}</span>
                    @if ($pricelist->type != 'price')
                      <span class="label label-default">{{ $pricelist->as_percent('amount') }}%</span>
                    @endif
                    @if ( $pricelist->price_is_tax_inc )
                        <br />
                        <span class="label label-info">{{ l('Tax Included', [], 'pricelists') }}</span>
                    @endif
                    </td>
                <td>{{ $pricelist->currency->name }}</td>
                <td>{{ $product->as_priceable($line_price) }}</td>
                <td>{{ $product->as_percentable( \App\Helpers\Calculator::discount( $product->price, $line_price, $pricelist->currency ) ) }}</td>
                <td>{{ $product->as_percentable( \App\Helpers\Calculator::margin( $product->cost_price, $line_price, $pricelist->currency ) ) }}</td>
                <td>
                @if ( $pricelist->currency->id == intval(AbiConfiguration::get('DEF_CURRENCY')) )
                  {{ $product->as_priceable( $line_price*(1.0+($product->tax->percent/100.0)) ) }}
                @endif
                </td>
                <td class="text-right">
                    <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('pricelistlines/' . $theprice->price_list_line_id . '/edit?back_route=' . urlencode('products/' . $product->id . '/edit#sales')) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
                </td>
            </tr>
            
            @endforeach

    @endif

        </tbody>
    </table>

       </div>
    </div>



</div>
<!-- Price List ENDS -->

</div>

