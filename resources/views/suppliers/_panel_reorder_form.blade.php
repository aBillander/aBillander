



{!! Form::open( ['method' => 'POST', 'id' => 'form-quantity-selector'] ) !!}

{!! Form::hidden('supplier_id', $supplier->id, array('id' => 'supplier_id')) !!}

{{--
               <!-- br><h4  class="text-info" xclass="panel-title">
                   {{l('Stock Availability')}}
               </h4><br -->
              <div xclass="page-header">
                  <div class="pull-right" xstyle="padding-top: 4px;">

                      <!-- a href="{{ URL::to($model_path.'/create') }}" class="btn xbtn-sm btn-info" 
                              title="{{l('Shipping Slip', 'layouts')}}"><i class="fa fa-plus"></i> <i class="fa fa-truck"></i> &nbsp;{{l('Shipping Slip', 'layouts')}}</a -->

                  </div>
                  <h3>
                      <span style="color: #dd4814;">{{l('Stock Availability')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3><br>        
              </div>
--}}


       <div class="table-responsive">

    <table id="products_availability" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th>{{l('Product')}}</th>
                <th>{{l('Stock', 'products')}}</th>

                <th>{{l('Measure Unit', 'products')}}</th>
                <th>{{l('Purchase Measure Unit', 'products')}}</th>
                <th>{{l('Last Purchase Price', 'products')}}</th>

            <th>{{ l('Allocated', 'products') }}</th>
            <th>{{ l('On Order', 'products') }}</th>
            <th>{{ l('Available', 'products') }}

                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"data-html="true"  
                              data-content="{{ l('Available Stock: <br />[Physical Stock] <br />+ [Orders to Suppliers] <br />- [Customer Orders] <br />+ [Not finished Production Orders] <br />- [Production Orders Reserves]', 'products') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>

            </th>
                <th>{{l('Minimum stock', 'products')}}

                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"data-html="true"  
                              data-content="{{ l('Reorder point', 'products') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>

            </th>

            <th>{{ l('Maximum stock', 'products') }}</th>
            <th>{{ l('Suggested Quantity', 'products') }}</th>

                        <th class="text-left button-pad active" style="width: 90px;">{{ l('Quantity', 'products') }} / <br />{{ l('Price') }} ({{ $supplier->currency->sign ?: $supplier->currency->name }})


                  <a class="btn btn-sm btn-info xbtn-pressure xbtn-sensitive xlines_quick_form  hide " title="{{l('Full quantity')}}" sxtyle="opacity: 0.65;" onclick="getDocumentAvailability(0)"><i class="fa fa-th"></i> </a>

                  <a class="btn btn-sm btn-info xbtn-grey xbtn-sensitive xcreate-document-service  hide " title="{{l('Quantity on-hand only')}}" xstyle="background-color: #2bbbad;" onclick="getDocumentAvailability(1)"><i class="fa fa-th-large"></i> </a>


                        </th>
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
                <td>{{ $product->supplymeasureunit->name }}

@if ( $product->measure_unit_id != $product->supplymeasureunit->id )
    <br />
    <span class="text-info">
    1 {{ $product->supplymeasureunit->name }} = {{ $product->as_percentable($product->supplymeasureunit_conversion_rate) }} x {{ $product->measureunit->name }}
    </span>
@endif
                </td>
                <td>{{ $product->as_price('last_purchase_price') }} / {{ $product->supplymeasureunit->name }}</td>
            <td>{{ $product->as_quantityable($product->quantity_allocated / $product->supplymeasureunit_conversion_rate) }}</td>
            <td>{{ $product->as_quantityable($product->quantity_onorder / $product->supplymeasureunit_conversion_rate)  }}</td>
            <td>{{ $product->as_quantityable(($product->quantity_onhand - $product->quantity_allocated + $product->quantity_onorder) / $product->supplymeasureunit_conversion_rate) }}</td>
                <td>{{ $product->as_quantityable($product->reorder_point / $product->supplymeasureunit_conversion_rate) }}</td>
            <td>{{ $product->as_quantityable($product->maximum_stock / $product->supplymeasureunit_conversion_rate) }}</td>
            <td>{{ $product->as_quantityable($product->quantity_reorder_suggested / $product->supplymeasureunit_conversion_rate) }} {{ $product->supplymeasureunit->name }}</td>

                <td class="text-right active">

<input name="dispatch[{{ $product->id }}]" class="form-control input-sm" type="text" size="3" maxlength="5" style="min-width: 0;
    xwidth: auto;
    display: inline;" value="{{ $product->as_quantityable($product->quantity_reorder_suggested / $product->supplymeasureunit_conversion_rate) }}" onclick="this.select()">


<input name="dispatch_price[{{ $product->id }}]" class="form-control input-sm" type="text" size="3" maxlength="5" style="min-width: 0;
    xwidth: auto; margin-top: 4px;
    display: inline;" value="{{ $product->as_price('last_purchase_price') }}" onclick="this.select()">
    
                </td>

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


    

            <div class="panel-body well" style="margin-top: 30px">

                  <div class="xpanel-footer pull-right" style="margin-top: 10px">

                        <a class="btn btn-info" href="javascript:void(0);" title="{{l('Confirm', 'layouts')}}" onclick = "this.disabled=true;$('#form-quantity-selector').attr('action', '{{ route( 'supplier.products.reorder', [$supplier->id] )}}');$('#form-quantity-selector').submit();return false;"><i class="fa fa-file-text-o"></i> &nbsp;{{l('New Order to Supplier')}}</a>
                  
                  </div>

            </div>


{!! Form::close() !!}
