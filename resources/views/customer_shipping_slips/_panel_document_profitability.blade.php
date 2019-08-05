



               <!-- br><h4  class="text-info" xclass="panel-title">
                   {{l('Profitability Analysis')}}
               </h4><br -->
              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Cost-benefit per line')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3><br>        
              </div>


    <div id="div_document_profit_details">
       <div class="table-responsive">

    <table id="document_lines_profit" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">{{l('Line #')}}</th>
                        <th class="text-center">{{l('Quantity')}}</th>
                        <th class="text-left">{{l('Reference')}}</th>
               <th class="text-left">{{l('Description')}}</th>
                        <th class="text-left">{{l('Price')}}</th>
                        <!-- th class="text-left">{{l('Disc. %')}}</th>
                        <th class="text-left">{{l('Net')}}</th -->
                        <th class="text-right">{{l('Cost')}}</th>
                        <th class="text-right">{{l('Margin 1 (%)')}}</th>
                        <th class="text-right">{{l('Margin Amount')}}</th>
@if ($document->salesrep)
                        <th class="text-right">{{l('Commission (%)')}}</th>
                        <th class="text-right">{{l('Margin 2 (%)')}}</th>
@endif
                      </tr>
                    </thead>

        <tbody>

    @if ($document->lines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($document->lines as $line)

            @if ($line->line_type == 'comment')
                @continue
            @endif
@php

$ecotax = optional( optional($line->product)->ecotax)->amount ?? 0.0;

@endphp
            <tr>
                <td>{{$line->line_sort_order }}</td>
                <td class="text-center">{{ $line->as_quantity('quantity') }}</td>
                <td>
                @if($line->line_type == 'shipping')
                  <i class="fa fa-truck abi-help" title="{{l('Shipping Cost')}}"></i> 
                @endif
                <a href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->reference }}</a></td>
                <td>
                {{ $line->name }}</td>
                <td class="text-right" title="{{ $ecotax }}">{{ $line->as_priceable( $line->unit_final_price - $ecotax ) }}</td>
                <td class="text-right">{{ $line->as_price('cost_price') }}</td>
                <td class="text-right">{{ $line->as_percentable( \App\Calculator::margin( $line->cost_price, $line->unit_final_price - $ecotax, $document->currency ) ) }}</td>
                <td class="text-right">{{ $line->as_priceable( ( $line->unit_final_price - $ecotax - $line->cost_price )*$line->quantity ) }}</td>



@if ($document->salesrep)
                        <th class="text-right"> </th>
                        <th class="text-right"></th>
@endif
            </tr>
            
            @endforeach

    @else
    <tr><td colspan="9">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td></tr>
    @endif

        </tbody>
    </table>

       </div>
    </div>



               
              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Cost-Benefit Analysis')}}</span> 

                    @if ( \App\Configuration::get('INCLUDE_SHIPPING_COST_IN_PROFIT') > 0 )
                      <span class="label label-danger" style="font-size: 55%;">{{ l('Shipping Cost included', [], 'layouts') }}</span>
                    @else
                      <span class="label label-warning" style="font-size: 55%;">{{ l('Shipping Cost excluded', [], 'layouts') }}</span>
                    @endif
                       
                  </h3><br>        
              </div>





    <div id="div_document_profit">
       <div class="table-responsive">

    <table id="document_profit" class="table table-hover">
        <thead>
            <tr>
                          <th class="text-left">{{l('Value')}}</th>
                          <th class="text-left">{{l('Total')}}</th>
                          <th class="text-left">{{l('Disc. %')}}</th>
                          <th class="text-left">{{l('Net')}}</th>
                          <th class="text-left">{{l('Total Disc. %')}}</th>
                          <th class="text-right">{{l('Cost')}}</th>
                          <th class="text-right">{{l('Margin 1 (%)')}}</th>
                          <th class="text-right">{{l('Margin Amount')}}</th>
@if ($document->salesrep)
                        <th class="text-right">{{l('Commission (%)')}}</th>
                        <th class="text-right">{{l('Margin 2 (%)')}}</th>
@endif
                      </tr>
                    </thead>

        <tbody>
@php

$document_total_discount_percent = $document->document_discount_percent + $document->document_ppd_percent
                                 - $document->document_discount_percent * $document->document_ppd_percent / 100.0;

@endphp

            <tr>
                <td>{{ $document->as_priceable($document->total_target_revenue) }}</td>
                <td>{{ $document->as_priceable($document->total_revenue) }}</td>
                <td>{{ $document->as_percentable( $document_total_discount_percent ) }}</td>
                <td>{{ $document->as_priceable($document->total_revenue_with_discount) }}</td>

                <td>{{ $document->as_percentable( 100.0 * ($document->total_target_revenue - $document->total_revenue_with_discount) / $document->total_target_revenue ) }}</td>

                <td class="text-right">{{ $document->as_priceable($document->total_cost_price) }}</td>
                <td class="text-right">{{ $document->as_percentable( \App\Calculator::margin( $document->total_cost_price, $document->total_revenue_with_discount, $document->currency ) ) }}</td>
                <td class="text-right">{{ $document->as_priceable( $document->total_revenue_with_discount - $document->total_cost_price ) }}</td>



@if ($document->salesrep)
                        <th class="text-right"> </th>
                        <th class="text-right"></th>
@endif
            </tr>

        </tbody>
    </table>

       </div>
    </div>




               <br>
               <br>

               <b>{{l('Margin')}}</b>: 
                    {{ \App\Configuration::get('MARGIN_METHOD') == 'CST' ?
                          l('Margin calculation is based on Cost Price', [], 'layouts') :
                          l('Margin calculation is based on Sales Price', [], 'layouts') }}
               <br>




