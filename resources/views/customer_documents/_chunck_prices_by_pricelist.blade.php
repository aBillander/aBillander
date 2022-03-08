


<!-- Price List -->

<div id="panel_sales_detail" style="padding-left: 15px; padding-right: 15px; padding-bottom: 20px;">

    <div xclass="page-header" onclick="$(this).next().toggle();">
        <h3>
            <span style="color: #dd4814;">{{ l('Price Lists', 'products') }}</span> &nbsp; 
            <a href="javascript::void();" class="btn btn-sm btn-grey"> <span class="caret"> </span> </a>
            {{-- <span style="color: #cccccc;">/</span> $product->name --}}
        </h3>        
    </div>

    <div id="div_aBook" style="display:none;">
       <div class="table-responsive">

    @if ($pricelists->count())
    <table id="aBook" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{l('Price List Name', 'products')}}</th>
                <th class="text-left">{{l('Currency', 'products')}}</th>
                <th class="text-left">{{l('Sales Price', 'products')}}</th>
                <th class="text-left">{{l('Discount (%)', 'products')}}</th>
                <th class="text-left">{{l('Margin (%)', 'products')}}</th>
                <!-- th class="text-left">{ {l('Price with Tax')} } </th>
                <th class="text-right"> </th -->
            </tr>
        </thead>
        <tbody>

            <tr style="color: #3a87ad; background-color: #d9edf7;">
                <td> </td>
                <td>{{ l('Base Price', 'products') }}</td>
                <td>{{ AbiContext::getContext()->currency->name }}</td>
                <td>{{ $product->as_price('price') }}</td>
                <td> - </td>
                <td>{{ $product->as_percentable( \App\Calculator::margin( $product->cost_price, $product->price ) ) }}</td>
                <!-- td>{ { $product->as_priceable( $product->price*(1.0+($product->tax->percent/100.0)) ) } }</td>
                <td class="text-right"> </td -->
            </tr>
            

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
                <td>{{ $product->as_percentable( \App\Calculator::discount( $product->price, $line_price, $pricelist->currency ) ) }}</td>
                <td>{{ $product->as_percentable( \App\Calculator::margin( $product->cost_price, $line_price, $pricelist->currency ) ) }}</td>
                <!-- td>
                @ i f ( $pricelist->currency->id == intval(AbiConfiguration::get('DEF_CURRENCY')) )
                  {{ $product->as_priceable( $line_price*(1.0+($product->tax->percent/100.0)) ) }}
                @ end if
                </td>
                <td class="text-right">
                    < ! -- a class="btn btn-sm btn-warning" href="{{ URL::to('pricelistlines/' . $theprice->price_list_line_id . '/edit?back_route=' . urlencode('products/' . $product->id . '/edit#sales')) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -- >
                </td -->
            </tr>
            
            @endforeach

        </tbody>
    </table>
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    @endif

       </div>
    </div>



</div>

<!-- Price List ENDS -->
