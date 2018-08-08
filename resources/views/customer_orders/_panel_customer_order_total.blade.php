
    <div xclass="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Totals') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $order->name }} -->
             {{-- include getSubtotals functions in billable trait --}}
        </h3>        
    </div>

    <div id="div_customer_order_total">
       <div class="table-responsive">

    <table id="order_total" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">

                    @if( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
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

        <tbody>
            <tr>
                <td>

                    @if( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ $order->as_price('total_lines_tax_incl') }}
                    @else
                    {{ $order->as_price('total_lines_tax_excl') }}
                    @endif

                </td>
                <td style="width:1px; white-space: nowrap;">

                    <div class="form-group">
                      @if ($order->locked || $order->status == 'closed' || $order->status == 'canceled')
                        {{ $order->as_percent('document_discount_percent') }}
                      @else
                      <div class="input-group">

                        <span class="input-group-addon input-sm"><strong>%</strong></span>

                        <input name="document_discount_percent" id="document_discount_percent" class="form-control input-sm col-xs-2" type="text" size="5" maxlength="10" style="width: auto;" value="{{ $order->as_percent('document_discount_percent') }}" onclick="this.select()" xonchange="add_discount_to_order($('#order_id').val());">

                        <span class="input-group-btn">
                          <button class="update-order-total btn btn-sm btn-lightblue" type="button" title="{{l('Apply', [], 'layouts')}}" xonclick="add_discount_to_order($('#order_id').val());">
                              <span class="fa fa-calculator"></span>
                          </button>
                        </span>

                      </div>
                      @endif
                    </div>

                </td>
                <td>{{ $order->as_price('total_tax_excl', $order->currency) }}</td>
                <td>{{ $order->as_priceable($order->total_tax_incl - $order->total_tax_excl) }}</td>
                <td class="text-right lead"><strong>{{ $order->as_price('total_tax_incl') }}</strong></td>
            </tr>
        </tbody>
    </table>

       </div>
    </div>
