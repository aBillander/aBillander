
    <div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Totals') }}</span> <!-- span style="color: #cccccc;">/</span> { { $order->name } } -->
             {{-- include getSubtotals functions in billable trait --}}
        </h3>        
    </div>

    <div id="div_customer_order_total">
       <div class="table-responsive">

    <table id="order_total" class="table table-hover">
        <thead>
            <tr>
               <th> </th>
               <th class="text-left">

                    @if( AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ l('Total Lines with Tax') }}
                    @else
                    {{ l('Total Lines') }}
                    @endif

               </th>

               <th class="text-left" style="width:1px; white-space: nowrap;">{{l('Discount')}}</th>

               <th class="text-left">{{l('Taxable Base')}}</th>
               <th class="text-left">{{l('Taxes')}}</th>

               <th class="text-right">{{l('Total')}}</th>
            </tr>
        </thead>
{{--
        <tbody>
            <tr>
                <td class="text-center">
                    <span class="badge" style="background-color: #3a87ad;">{{ $order->currency->iso_code }}</span>
                </td>
                <td style="vertical-align: middle;">

                    @if( AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ $order->as_price('total_lines_tax_incl') }}
                    @else
                    {{ $order->as_price('total_lines_tax_excl') }}
                    @endif

                </td>
                <td style="width:1px; white-space: nowrap;vertical-align: middle;">

                    <div xclass="form-group">
                      @if ( $order->editable )
                      <div class="input-group">

                        <span class="input-group-addon input-sm"><strong>%</strong></span>

                        <input name="document_discount_percent" id="document_discount_percent" class="input-update-order-total form-control input-sm col-xs-2" type="text" size="5" maxlength="10" style="width: auto;" value="{{ $order->as_percent('document_discount_percent') }}" onclick="this.select()" xonchange="add_discount_to_order($('#order_id').val());">

                        <span class="input-group-btn">
                          <button class="update-order-total btn btn-sm btn-lightblue" type="button" title="{{l('Apply', [], 'layouts')}}" xonclick="add_discount_to_order($('#order_id').val());">
                              <span class="fa fa-calculator"></span>
                          </button>
                        </span>

                      </div>
                      @else
                        {{ $order->as_percent('document_discount_percent') }}
                      @endif
                    </div>

                </td>
                <td style="vertical-align: middle;">{{ $order->as_price('total_currency_tax_excl', $order->currency) }}</td>
                <td style="vertical-align: middle;">{{ $order->as_priceable($order->total_currency_tax_incl - $order->currency_total_tax_excl) }}</td>
                <td class="text-right lead" style="vertical-align: middle;"><strong>{{ $order->as_price('total_currency_tax_incl') }}</strong></td>
            </tr>

@if ( $order->currency_conversion_rate != 1.0 )
            <tr>
                <td class="text-center">
                    <span class="badge" style="background-color: #3a87ad;">{{ AbiContext::getContext()->currency->iso_code }}</span>
                </td>
                <td style="vertical-align: middle;">
<!--
                    @if( AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ $order->as_price('total_lines_tax_incl') }}
                    @else
                    {{ $order->as_price('total_lines_tax_excl') }}
                    @endif
-->
                </td>
                <td>

                </td>
                <td style="vertical-align: middle;">{{ $order->as_price('total_tax_excl', $order->currency) }}</td>
                <td style="vertical-align: middle;">{{ $order->as_priceable($order->total_tax_incl - $order->total_tax_excl) }}</td>
                <td class="text-right lead" style="vertical-align: middle;"><strong>{{ $order->as_price('total_tax_incl') }}</strong></td>
            </tr>
@endif

        </tbody>
--}}
    </table>

       </div>
    </div>
