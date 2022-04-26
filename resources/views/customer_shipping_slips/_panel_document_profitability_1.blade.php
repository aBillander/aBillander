



               <!-- br><h4  class="text-info" xclass="panel-title">
                   {{l('Profitability Analysis')}}
               </h4><br -->
              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Cost-benefit per line')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                      <span class="label alert-warning" style="font-size: 55%;">

                    {{ AbiConfiguration::get('MARGIN_METHOD') == 'CST' ?
                          l('Margin calculation is based on Cost Price', [], 'layouts') :
                          l('Margin calculation is based on Sales Price', [], 'layouts') }}

                      </span>
                       
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
                        <th class="text-left">{{l('Price')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Ecotax not included.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
                        <!-- th class="text-left">{{l('Disc. %')}}</th>
                        <th class="text-left">{{l('Net')}}</th -->
                        <th class="text-right">{{l('Cost')}}</th>
                        <th class="text-right">{{l('Margin 1 (%)')}}</th>
                        <th class="text-right">{{l('Margin Amount')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Shown in Document Currency.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
@if ($document->salesrep)
                        <th class="text-right">{{l('Commission (%)')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Commission is supposed to be on Sale Price including Ecotax. Commission is higher when calculated on Sale Price excluding Ecotax.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
                        <th class="text-right">{{l('Margin 2 (%)')}}</th>
                        <th class="text-right">{{l('Margin Amount 2')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Shown in Document Currency.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
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
                <td title="{{ $line->id }}">{{ $line->line_sort_order }}</td>
                <td class="text-center">{{ $line->as_quantity('quantity') }}
                        @if ($line->extra_quantity > 0.0 && $line->extra_quantity_label != '')
                            <p class="text-right text-info">
                                +{{ $line->as_quantity('extra_quantity') }}{{ l(' extra') }}

                                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                                    xdata-trigger="focus"
                                    data-html="true" 
                                    data-content="{{ $line->extra_quantity_label }}">
                                    <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                                 </a>
                            </p>
                        @endif</td>
                <td>
                @if($line->line_type == 'shipping')
                  <i class="fa fa-truck abi-help" title="{{l('Shipping Cost')}}"></i> 
                @endif
                <a href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->reference }}</a></td>
                <td>
                {{ $line->name }}</td>
                <td class="text-right button-pad" title="{{ l('Ecotax', 'customerdocuments') }}: {{ $line->as_priceable( $ecotax ) }} {{ $document->currency->sign }}">{{ $line->as_priceable( $line->unit_final_price - $ecotax ) }}<br />
                  <span class="alert-success">{{ $line->as_priceable( $line->unit_final_price ) }} - {{ $line->as_priceable( $ecotax ) }}</span></td>
                <td class="text-right">{{ $line->as_price('cost_price') }}</td>
                <td class="text-right">{{ $line->as_percentable( \App\Helpers\Calculator::margin( $line->cost_price * $line->quantity_total, ($line->unit_final_price - $ecotax) * $line->quantity, $document->currency ) ) }}</td>
                <td class="text-right">{{ $line->as_priceable( ( $line->unit_final_price - $ecotax - $line->cost_price )*$line->quantity - $line->cost_price*$line->extra_quantity ) }}</td>



@if ($document->salesrep)
                <td class="text-right">{{ $line->as_percentable( 100.0 * $line->getSalesRepCommission() / (( $line->unit_final_price - $ecotax )*$line->quantity) ) }}<br />

                  <span class="alert-success">{{ $line->as_percent('commission_percent') }}</span></td>

                <td class="text-right">{{ $line->as_percentable( \App\Helpers\Calculator::margin( $line->cost_price*$line->quantity_total, ( $line->unit_final_price - $ecotax )*$line->quantity - $line->getSalesRepCommission(), $document->currency ) ) }}</td>

                <td class="text-right">{{ $line->as_priceable( ( $line->unit_final_price - $ecotax - $line->cost_price )*$line->quantity - $line->cost_price*$line->extra_quantity - $line->getSalesRepCommission() ) }}</td>
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

                    @if ( AbiConfiguration::get('INCLUDE_SHIPPING_COST_IN_PROFIT') > 0 )
                      <span class="label alert-danger" style="font-size: 55%;">{{ l('Shipping Cost included', [], 'layouts') }}</span>
                    @else
                      <span class="label alert-warning" style="font-size: 55%;">{{ l('Shipping Cost excluded', [], 'layouts') }}</span>
                    @endif
                       
                  </h3><br>        
              </div>





    <div id="div_document_profit">
       <div class="table-responsive">

    <table id="document_profit" class="table table-hover">
        <thead>
            <tr>
                          <th class="text-left">{{l('Value')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Products Value after Product Records.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
                          <th class="text-left">{{l('Total')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Document Products Total.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
                          <th class="text-left">{{l('Disc. %')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Document Discounts: Document (Header) Discount plus Document Prompt Payment Discount.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
                          <th class="text-left">{{l('Disc.')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Document Discount Lines.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
                          <th class="text-left">{{l('Net')}}</th>
                          <th class="text-left">{{l('Total Disc. %')}}</th>
                          <th class="text-right">{{l('Cost')}}</th>
                          <th class="text-right">{{l('Margin 1 (%)')}}</th>
                          <th class="text-right">{{l('Margin Amount')}}</th>
@if ($document->salesrep)
                        <th class="text-right">{{l('Commission (%)')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body"
                                    data-content="{{ l('Calculated Commission for the entire Document (average).') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
                        <th class="text-right">{{l('Margin 2 (%)')}}</th>
                        <th class="text-right">{{l('Margin Amount 2')}}</th>
@endif
                      </tr>
                    </thead>

        <tbody>

            <tr>
                <td>{{ $document->as_priceable($document->total_target_revenue) }}</td>
                <td>{{ $document->as_priceable($document->total_revenue) }}</td>
                <td>{{ $document->as_percentable( $document->document_total_discount_percent ) }}</td>
                <td>{{ $document->as_percentable( $document->document_total_discount_lines ) }}</td>
                <td>{{ $document->as_priceable($document->total_revenue_with_discount) }}</td>

                <td>
@if($document->total_target_revenue != 0.0)
                  {{ $document->as_percentable( 100.0 * ($document->total_target_revenue - $document->total_revenue_with_discount) / $document->total_target_revenue ) }}
@endif
                </td>

                <td class="text-right">{{ $document->as_priceable($document->total_cost_price) }}</td>
                <td class="text-right">{{ $document->as_percentable( \App\Helpers\Calculator::margin( $document->total_cost_price, $document->total_revenue_with_discount, $document->currency ) ) }}</td>
                <td class="text-right">{{ $document->as_priceable( $document->total_revenue_with_discount - $document->total_cost_price ) }}</td>



@if ($document->salesrep)
                <td class="text-right">
@if($document->total_target_revenue != 0.0)
                  {{ $document->as_percentable( 100.0 * $document->getSalesRepCommission() / $document->total_revenue ) }}
@endif
                </td>

                <td class="text-right">{{ $document->as_percentable( \App\Helpers\Calculator::margin( $document->total_cost_price, $document->total_revenue_with_discount - $document->getSalesRepCommission(), $document->currency ) ) }}</td>
                <td class="text-right">{{ $document->as_priceable( $document->total_revenue_with_discount - $document->getSalesRepCommission() - $document->total_cost_price ) }}</td>
@endif
            </tr>

        </tbody>
    </table>

       </div>
    </div>




               <br>
               <br>

               <b>{{l('Margin')}}</b>: 
                    {{ l('Only Product Lines and Discount Lines are considered, and Shipping Lines depending on Configuration.') }}
               <br>
