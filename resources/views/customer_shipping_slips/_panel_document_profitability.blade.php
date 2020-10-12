



               <!-- br><h4  class="text-info" xclass="panel-title">
                   {{l('Profitability Analysis')}}
               </h4><br -->
              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Cost-benefit per line')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                      <span class="label alert-warning" style="font-size: 55%;">

                    {{ \App\Configuration::get('MARGIN_METHOD') == 'CST' ?
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
            

            @foreach ($document->profitablelines as $line)
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

@if ( \App\Configuration::isTrue('ENABLE_ECOTAXES') && ($line->line_type == 'product') && $line->product->ecotax )

                <td class="text-right button-pad" title="{{ l('Ecotax', 'customerdocuments') }}: {{ $line->as_priceable( $line->ecotax_amount ) }} {{ $document->currency->sign }}">

        @if ( $line->ecotax_amount != $line->product->ecotax->amount )
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                  xdata-trigger="focus"
                  data-html="true" 
                  data-content="{{ l('Line Ecotax Amount is different from Product Ecotax Amount. You must "Update Line Ecotaxes" (button below).') }}">
                  <i class="fa fa-warning abi-help" style="color: #df382c;"></i>
               </a>
        @endif

                  {{ $line->as_priceable( $line->profit_final_price ) }}<br />
                  <span class="alert-success">{{ $line->as_priceable( $line->unit_final_price ) }} - {{ $line->as_priceable( $line->ecotax_amount ) }}</span></td>
@else

                <td class="text-right button-pad" title="">{{ $line->as_priceable( $line->profit_final_price ) }}</td>
@endif
                <td class="text-right">{{ $line->as_price('cost_price') }}</td>

                <td class="text-right">{{ $line->as_percentable( $line->marginPercent() ) }}</td>

                <td class="text-right">{{ $line->as_priceable( $line->marginAmount() ) }}</td>



@if ($document->salesrep)
                <td class="text-right">{{ $line->as_percentable( 100.0 * $line->getSalesRepCommission() / (( $line->profit_final_price )*$line->quantity) ) }}<br />

                  <span class="alert-success">{{ $line->as_percent('commission_percent') }}</span></td>

                <td class="text-right">{{ $line->as_percentable( $line->marginTwoPercent() ) }}</td>

                <td class="text-right">{{ $line->as_priceable( $line->marginTwoAmount() ) }}</td>
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
                <td>{{ $document->as_priceable($document->getTotalTargetRevenue()) }}</td>
                <td>{{ $document->as_priceable($document->getTotalRevenue()) }}</td>
                <td>{{ $document->as_percentable( $document->document_total_discount_percent ) }}</td>
                <td>{{ $document->as_percentable( $document->document_total_discount_lines ) }}</td>
                <td>{{ $document->as_priceable($document->getTotalRevenueWithDiscount()) }}</td>

                <td>
@if($document->getTotalTargetRevenue() != 0.0)
                  {{ $document->as_percentable( 100.0 * ($document->getTotalTargetRevenue() - $document->getTotalRevenueWithDiscount()) / $document->getTotalTargetRevenue() ) }}
@endif
                </td>

                <td class="text-right">{{ $document->as_priceable($document->getTotalCostPrice()) }}</td>

                <td class="text-right">{{ $document->as_percentable( $document->marginPercent() ) }}</td>

                <td class="text-right">{{ $document->as_priceable( $document->marginAmount() ) }}</td>



@if ($document->salesrep)
                <td class="text-right">
@if($document->getTotalTargetRevenue() != 0.0)
                  {{ $document->as_percentable( 100.0 * $document->getSalesRepCommission() / $document->total_revenue ) }}
@endif
                </td>

                <td class="text-right">{{ $document->as_percentable( $document->marginTwoPercent() ) }}</td>

                <td class="text-right">{{ $document->as_priceable( $document->marginTwoAmount() ) }}</td>
@endif
            </tr>

        </tbody>
    </table>

       </div>
    </div>




               <br>
               <br>

               <b>{{l('Margin')}}</b>. 
                    {{ l('Document Lines to be included in calculations') }}: <br>

                    <ul>
                      <li>{{ l('Product Lines.') }}</li>
                      <li>{{ l('Discount Lines.') }}</li>
                      <li>{{ l('Sevice Lines.') }} {{ l('Depending on Configurations (:yn).', ['yn' => \App\Configuration::isTrue('INCLUDE_SERVICE_LINES_IN_PROFIT') ? l('Yes', 'layouts') : l('No', 'layouts')]) }}</li>
                      <li>{{ l('Shipping Lines.') }} {{ l('Depending on Configurations (:yn).', ['yn' => \App\Configuration::isTrue('INCLUDE_SHIPPING_COST_IN_PROFIT') ? l('Yes', 'layouts') : l('No', 'layouts')]) }}</li>
                    </ul>

               <br>
