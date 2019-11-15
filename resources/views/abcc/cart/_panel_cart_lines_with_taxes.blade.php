<div id="div_cart_lines">
          <div class="table-responsive">

@if ($cart->cartlines->count())
    <table id="cart_lines" class="table table-hover">
        <thead>
            <tr>
              <th class="text-right">ID</th>
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
               <th class="text-right">
                  <span class="button-pad">{{ l('Customer Price') }}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-content="{{ l('Prices are exclusive of Tax', 'abcc/catalogue') }}
@if( \App\Configuration::isTrue('ENABLE_ECOTAXES') )
    . 
    {!! l('Prices are inclusive of Ecotax', 'abcc/catalogue') !!}
@endif
                  ">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a></span></th>


                    @if($config['display_with_taxes'])
                        <th class="text-right">
                          <span class="button-pad">{{ l('Customer Price (with Tax)') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                              xdata-trigger="focus"
                              data-content="{{ l('Prices are inclusive of Tax', 'abcc/catalogue') }}">
                              <i class="fa fa-question-circle abi-help"></i>
                           </a>
                          </span>
                        </th>

                        <th>{{ l('Tax') }}</th>
                    @endif



               <th class="text-right">{{ l('Total') }}
                        <br/><span class="button-pad text-muted">{{ l('Without Tax') }}</span></th>
              <th class="text-right"> </th>
            </tr>
        </thead>

        <tbody class="sortable ui-sortable">
  @foreach ($cart->cartlines as $line)
    <tr>
      <td title="{{ $line->line_sort_order }}">{{ $line->id }}</td>
      <td title="{{ $line->product->id }}">@if ($line->product->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else {{ $line->product->reference }}
                @endif</td>

      <td>
                            @if ($line->img)
                                <a class="view-image" data-html="false" data-toggle="modal"
                                   href="{{ URL::to( \App\Image::pathProducts() . $line->img->getImageFolder() . $line->img->id . '-large_default' . '.' . $line->img->extension ) }}"
                                   data-title="{{ l('Product Images') }} :: {{ $line->product->name }}"
                                   data-caption="({{$line->img->id}}) {{ $line->img->caption }}"
                                   onClick="return false;" title="{{l('View Image')}}">

                                    <img src="{{ URL::to( \App\Image::pathProducts() . $line->img->getImageFolder() . $line->img->id . '-mini_default' . '.' . $line->img->extension ) . '?'. 'time='. time() }}"
                                         alt="{{ $line->product->name }}" style="border: 1px solid #dddddd;">
                                </a>
                            @endif
      </td>

      <td>{{ $line->product->name }}
          @if( \App\Configuration::isTrue('ENABLE_ECOTAXES') && $line->product->ecotax )
              <br />
              {{ l('Ecotax: ', 'abcc/catalogue') }} {{ $line->product->ecotax->name }} ({{ abi_money( $line->product->getEcotax() ) }})
          @endif
      </td>

      <td style="white-space:nowrap">
@if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) != 'none')
            @if( \App\Configuration::get( 'ABCC_STOCK_SHOW' ) == 'amount')
              <div class="progress progress-striped" style="margin-bottom: auto !important">
                <div class="progress-bar progress-bar-{{ $line->product->stock_badge }}" title="{{ l('stock.badge.'.$line->product->stock_badge, 'abcc/layouts') }}" style="width: 100%">
                        <span class="badge" style="color: #333333; background-color: #ffffff;">{{ $line->product->as_quantity('quantity_onhand') }}</span>
                </div>
              </div>
            @else
              <div class="progress progress-striped" style="width: 34px; margin-bottom: auto !important">
                <div class="progress-bar progress-bar-{{ $line->product->stock_badge }}" title="{{ l('stock.badge.'.$line->product->stock_badge, 'abcc/layouts') }}" style="width: 100%">
                </div>
              </div>
            @endif
@endif
      </td>

        <td style="width:1px; white-space: nowrap;xvertical-align: top;">

            <div xclass="form-group">
              <div class="input-group" style="width: 81px;">

                <input class="input-line-quantity form-control input-sm col-xs-2" data-id="{{$line->id}}" data-quantity="{{ (int) $line->quantity }}" type="text" size="5" maxlength="5" style="xwidth: auto;" value="{{ (int) $line->quantity }}" onclick="this.select()" >

                <span class="input-group-btn">
                  <button class="update-line-quantity btn btn-sm btn-lightblue" data-id="{{$line->id}}" data-quantity="{{ (int) $line->quantity }}" type="button" title="{{l('Apply', [], 'layouts')}}" xonclick="add_discount_to_order($('#order_id').val());">
                      <span class="fa fa-calculator"></span>
                  </button>
                </span>

              </div>
            </div>

                  @if ( $cart->customer->getExtraQuantityRule( $line->product, $cart->customer->currency ) )
                      <div class="pull-left">
                          <p class="text-center text-info">
                              +{{ $line->as_quantity('extra_quantity') }}{{ l(' extra') }}

                               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                                  xdata-trigger="focus"
                                  data-html="true" 
                                  data-content="{{ $line->extra_quantity_label }}<br />
                                                {{ l('Promo: You pay :npay and get :nget',
                                                ['npay' => (int)$line->quantity ,
                                                 'nget' => (int)($line->quantity + $line->extra_quantity) ]) }}">
                                  <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                               </a>
                          </p>
                      </div>
                  @endif

      </td>

      <td class="text-right">

{{--
          {{ $line->as_price('unit_customer_price') }}

          <!-- p class="text-info">{{ $line->as_priceable($line->unit_customer_price + $line->product->getEcotax()) }}</p -->

@if ( $line->product->hasQuantityPriceRules( \Auth::user()->customer ) )

          <a class="btn btn-sm btn-custom show-pricerules" href="#" data-target='#myModalShowPriceRules' data-id="{{ $line->product->id }}" data-toggle="modal" onClick="return false;" title="{{ l('Show Special Prices', 'abcc/catalogue') }} { {-- $line->product->hasQuantityPriceRules( \Auth::user()->customer ) --} }"><i class="fa fa-thumbs-o-up"></i></a>

@endif
--}}


                            @if ( $line->product->getPriceRulesByCustomer( \Auth::user()->customer )->count() > 0 )
                                <a class="btn btn-sm btn-custom show-pricerules pull-right" href="#" data-target='#myModalShowPriceRules'
                                   data-id="{{ $line->product->id }}" data-toggle="modal" onClick="return false;"
                                   title="{{ l('Show Special Prices', 'abcc/catalogue') }}">
                                    <i class="fa fa-thumbs-o-up"></i>
                                </a>
                            @endif

                            <div class="pull-right">
                                {{ $line->as_price('unit_customer_final_price') }}{{ $cart->currency->sign }}
                                @if ($line->unit_customer_final_price != $line->unit_customer_price)
                                    <p class="text-info crossed">
                                        {{ $line->as_priceable($line->unit_customer_price) }}{{-- $cart->currency->sign --}}
                                    </p>
                                @endif
                            </div>
{{--
                            @if ($line->product->has_extra_item_applied)
                                <div class="pull-left">
                                    <p class="text-info">
                                        +{{ $line->product->extra_item_qty }}{{ l(' extra') }}
                                    </p>
                                </div>
                            @endif
--}}
{{--
                            @if ($line->extra_quantity)
                                <div class="pull-left">
                                    <p class="text-info">
                                        {{ $line->extra_quantity_label }}
                                    </p>
                                </div>
                            @endif
--}}
      </td>

                        @if($config['display_with_taxes'])
                            <td class="text-right">
                                {{ $line->as_priceable($line->unit_customer_final_price * ( 1.0 + $line->tax_percent / 100.0 )) }}
                            </td>

                            <td class="text-right">{{$line->as_percent('tax_percent', 1)}}%</td>
                        @endif

      <td class="text-right">{{ $line->as_priceable($line->total_tax_incl) }}
            <br/><span class="button-pad text-muted">{{ $line->as_priceable($line->quantity * $line->unit_customer_final_price) }}</span>
      </td>

                <td class="text-right button-pad">
                    <!-- a class="btn btn-sm btn-info" title="XXXXXS" onClick="loadcustomerorderlines();"><i class="fa fa-pencil"></i></a -->
                    
                    <!-- a class="btn btn-sm btn-warning edit-order-line" data-id="18" title="Modificar" onclick="return false;"><i class="fa fa-pencil"></i></a -->
                    
                    <a class="btn btn-sm btn-danger delete-cart-line" data-id="{{$line->id}}" title="{{l('Delete', [], 'layouts')}}" data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" data-title="{{ '('.$line->id.') ['.$line->reference.'] '.$line->name }}" onclick="return false;"><i class="fa fa-trash-o"></i></a>

                </td>
            </tr>

                    @if (0 && $line->extra_quantity)
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="6">
                                {{ $line->extra_quantity_label }}<br />
                                {{ l('Promo: You pay :npay and get :nget',
                                ['npay' => (int)$line->quantity ,
                                 'nget' => (int)($line->quantity + $line->extra_quantity) ]) }}
                            </td>
                        </tr>
                    @endif
  @endforeach


@include('abcc.cart._panel_cart_total_with_taxes')


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
