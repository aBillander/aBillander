
    <div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Totals') }} include getSubtotals functions in billable trait</span> <!-- span style="color: #cccccc;">/</span> {{ $order->name }} -->
        </h3>        
    </div>

    <div id="div_customer_order_lines">
       <div class="table-responsive">

    <table id="order_lines" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">{{l('Total Lines')}}</th> {{-- Con tax o no depende de la configuración de meter precios con impuesto incluido --}}
               <th class="text-left">{{l('Discount')}}</th>
               <th> </th>

               <th class="text-left">{{l('Taxable Base')}}</th>
               <th class="text-left">{{l('Taxes')}}</th>

               <th class="text-right">{{l('Total')}}</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $order->total_tax_excl }}</td>
                <td>{{ $order->document_discount_percent }} %</td>
                <td style="width:1px; white-space: nowrap;">
                   
                  <button class="btn btn-sm btn-lightblue" type="button" title="{{l('Edit', [], 'layouts')}}" onclick="get_currency_rate($('#currency_id').val());">
                      <span class="fa fa-calculator"></span>
                  </button>

                </td>
                <td>{{ $order->total_tax_excl }}</td>
                <td>{{ $order->total_tax_incl - $order->total_tax_excl }}</td>
                <td class="text-right lead"><strong>{{ $order->total_tax_incl }}</strong></td>
            </tr>
        </tbody>
    </table>

       </div>
    </div>
