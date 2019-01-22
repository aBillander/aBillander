<div id="div_cart_lines">
          <div class="table-responsive">

@if ($cart->cartlines->count())
    <table id="cart_lines" class="table table-hover">
        <thead>
            <tr>
              <th>{{ l('Reference') }}</th>
              <th colspan="2">{{ l('Product Name') }}</th>
              <th>
@if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) != 'none')
                  {{ l('Stock') }}
@endif
               </th>
               <th class="text-center button-pad">{{ l('Quantity') }}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-content="{{ l('Change Quantity and press [Enter] or click button on the right.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a></th>
               <th class="text-right">{{ l('Customer Price') }}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-content="{{ l('Prices are exclusive of Tax') }}">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a></th>
               <th class="text-right">{{ l('Total') }}</th>
              <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody class="sortable ui-sortable">
  @foreach ($cart->cartlines as $line)
    <tr>
      <!-- td>{{ $line->id }}</td -->
      <td title="{{ $line->product->id }}">@if ($line->product->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else {{ $line->product->reference }}
                @endif</td>

      <td>
@php
  $img = $line->product->getFeaturedImage();
@endphp
@if ($img)
              <a class="view-image" data-html="false" data-toggle="modal" 
                     href="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-large_default' . '.' . $img->extension ) }}"
                     data-content="{{l('You are going to view a record. Are you sure?')}}" 
                     data-title="{{ l('Product Images') }} :: {{ $line->product->name }} " 
                     data-caption="({{$img->id}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('View Image')}}">

                      <img src="{{ URL::to( \App\Image::$products_path . $img->getImageFolder() . $img->id . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">
              </a>
@endif
      </td>

      <td>{{ $line->product->name }}</td>

      <td style="white-space:nowrap">
@if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) != 'none')
            @if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) == 'amount')
              <div class="progress progress-striped">
                <div class="progress-bar progress-bar-{{ $line->product->stock_badge }}" title="{{ l('stock.badge.'.$line->product->stock_badge, 'abcc/layouts') }}" style="width: 100%">
                        <span class="badge" style="color: #333333; background-color: #ffffff;">{{ $line->product->as_quantity('quantity_onhand') }}</span>
                </div>
              </div>
            @else
              <div class="progress progress-striped" style="width: 34px">
                <div class="progress-bar progress-bar-{{ $line->product->stock_badge }}" title="{{ l('stock.badge.'.$line->product->stock_badge, 'abcc/layouts') }}" style="width: 100%">
                </div>
              </div>
            @endif
@endif
      </td>

        <td style="width:1px; white-space: nowrap;vertical-align: top;">

            <div xclass="form-group">
              <div class="input-group" style="width: 72px;">

                <input class="input-line-quantity form-control input-sm col-xs-2" data-id="{{$line->id}}" data-quantity="{{ (int) $line->quantity }}" type="text" size="5" maxlength="5" style="xwidth: auto;" value="{{ (int) $line->quantity }}" onclick="this.select()" >

                <span class="input-group-btn">
                  <button class="update-line-quantity btn btn-sm btn-lightblue" data-id="{{$line->id}}" data-quantity="{{ (int) $line->quantity }}" type="button" title="{{l('Apply', [], 'layouts')}}" xonclick="add_discount_to_order($('#order_id').val());">
                      <span class="fa fa-calculator"></span>
                  </button>
                </span>

              </div>
            </div>

      </td>

      <td class="text-right">{{ $line->as_price('unit_customer_price') }}</td>

      <td class="text-right">{{ $line->as_priceable($line->quantity * $line->unit_customer_price) }}</td>

                <td class="text-right button-pad">
                    <!-- a class="btn btn-sm btn-info" title="XXXXXS" onClick="loadcustomerorderlines();"><i class="fa fa-pencil"></i></a -->
                    
                    <!-- a class="btn btn-sm btn-warning edit-order-line" data-id="18" title="Modificar" onclick="return false;"><i class="fa fa-pencil"></i></a -->
                    
                    <a class="btn btn-sm btn-danger delete-cart-line" data-id="{{$line->id}}" title="{{l('Delete', [], 'layouts')}}" data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" data-title="{{ '('.$line->id.') ['.$line->reference.'] '.$line->name }}" onclick="return false;"><i class="fa fa-trash-o"></i></a>

                </td>
            </tr>
  @endforeach

{{-- Totals --}}
<tr class="info">
      <td></td>

      <td>
      </td>

      <td colspan="2"><h3>
            <span style="color: #dd4814;">{{ l('Total') }}</span>
        </h3></td>

        <td class="text-center lead"><h3>{{ $cart->quantity }}</h3></td>

      <td  class="text-center lead" colspan="3"><h3>{{ $cart->as_priceable($cart->amount) }} {{ $cart->currency->sign }}</h3></td>
</tr>

        </tbody>
    </table>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif


<input id="cart_nbr_items" name="cart_nbr_items" type="hidden" value="{{ $cart->nbrItems() }}">

       </div>

</div><!-- div id="div_cart_lines" -->
