
{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="inventory" name="tab_name" id="tab_name">

   <div class="panel-body">

<!-- Inventory -->

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-stock_control">
                     {!! Form::label('stock_control', l('Stock Control?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('stock_control', '1', true, ['id' => 'stock_control_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('stock_control', '0', false, ['id' => 'stock_control_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('reorder_point') ? 'has-error' : '' }}">
                     {{ l('Reorder point') }}
                     {!! Form::text('reorder_point', null, array('class' => 'form-control', 'id' => 'reorder_point')) !!}
                     {!! $errors->first('reorder_point', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('maximum_stock') ? 'has-error' : '' }}">
                     {{ l('Maximum stock') }}
                     {!! Form::text('maximum_stock', null, array('class' => 'form-control', 'id' => 'maximum_stock')) !!}
                     {!! $errors->first('maximum_stock', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
              <div class="form-group col-lg-6 col-md-6 col-sm-6">
    <div id="div_warehouses">
       <div class="table-responsive">

    <table id="products" class="table table-hover">
      <thead>
        <tr>
          <th>{{ l('Warehouse') }}</th>
          <th>{{ l('Quantity') }}</th>
          <th class="text-right"> </th>
        </tr>
      </thead>
      <tbody>
      @foreach ($product->warehouses as $wh)
        <tr>
          <td>{{ $wh->alias }}</td>
          <td>{{ $product->as_quantityable($wh->pivot->quantity) }}</td>
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
                     {!! Form::text('quantity_onhand', null, array('class' => 'form-control', 'id' => 'quantity_onhand', 'onfocus' => 'this.blur()')) !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6">
                     {{ l('Quantity on order') }}
                     {!! Form::text('quantity_onorder', null, array('class' => 'form-control', 'id' => 'quantity_onorder', 'onfocus' => 'this.blur()')) !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6">
                     {{ l('Quantity allocated') }}
                     {!! Form::text('quantity_allocated', null, array('class' => 'form-control', 'id' => 'quantity_allocated', 'onfocus' => 'this.blur()')) !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6">
                     <strong>{{ l('Available') }}</strong>
                     {!! Form::text('available', null, array('class' => 'form-control', 'id' => 'available', 'onfocus' => 'this.blur()')) !!}
                  </div>
              </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>

<!-- Inventory ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>


    {!! Form::close() !!}
    