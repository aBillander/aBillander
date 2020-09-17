


        <div class="row">
              <div class="form-group col-lg-6 col-md-6 col-sm-6">
    <div id="div_warehouses">
       <div class="table-responsive">

    <table id="products" class="table table-hover">
      <thead>
        <tr>
          <th>{{ l('Warehouse') }}</th>
          <th>{{ l('Quantity') }}</th>
          <th>{{ l('Lots') }}</th>
          <th>{{ l('Not in Lots') }}</th>
          <th class="text-right"> </th>
        </tr>
      </thead>
      <tbody>
      @foreach ($product->warehouses as $wh)
        <tr>
          <td>{{ $wh->alias }}</td>
          <td>{{ $product->as_quantityable($wh->pivot->quantity) }}</td>
          <td>{{ $product->as_quantityable($product->getLotStockByWarehouse( $wh->id )) }}</td>
          <td>{{ $product->as_quantityable($wh->pivot->quantity - $product->getLotStockByWarehouse( $wh->id )) }}</td>
               <td class="text-right">
                </td>
        </tr>
      @endforeach
        <tr>
          <td class="text-right">{{ l('TOTAL') }}:</td>
          <td>{{ $product->as_quantity('quantity_onhand') }}</td>
               <td class="text-right">
                </td>
        </tr>
        </tbody>
    </table>

       </div>
    </div>




              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6">

                     <strong>{{ l('Quantity on hand') }}</strong>
                     <div class="form-control" id="quantity_onhand">
                     		{{ $product->as_quantity('quantity_onhand') }}
                     </div>

                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6">

                     {{ l('Quantity on order') }}
                     <div class="form-control" id="quantity_onorder">
                     		{{ $product->as_quantity('quantity_onorder') }}
                     </div>

                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6">

                     {{ l('Quantity allocated') }}
                     <div class="form-control" id="quantity_allocated">
                     		{{ $product->as_quantity('quantity_allocated') }}
                     </div>

                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6">

                     <strong>{{ l('Available') }}</strong>
                     <div class="form-control" id="quantity_available">
                     		{{ $product->as_quantityable($product->quantity_available) }}
                     </div>

                  </div>
              </div>
        </div>
