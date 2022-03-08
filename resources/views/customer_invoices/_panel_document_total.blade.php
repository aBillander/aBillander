
    <div xclass="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Totals') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} -->
             {{-- include getSubtotals functions in billable trait --}}
        </h3>        
    </div>

    <div id="div_document_total">
       <div class="table-responsive">

    <table id="document_total" class="table table-hover">
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

               <th class="text-left" style="width:1px; white-space: nowrap;">{{l('Prompt Payment')}}</th>

               <th class="text-left">{{l('Taxable Base')}}</th>
               <th class="text-left">{{l('Taxes')}}</th>

               <th class="text-right">{{l('Total')}}</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="text-center">
                    <span class="badge" style="background-color: #3a87ad;">{{ $document->currency->iso_code }}</span>
                </td>
                <td style="vertical-align: middle;">

                    @if( AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ $document->as_price('total_lines_tax_incl') }}
                    @else
                    {{ $document->as_price('total_lines_tax_excl') }}
                    @endif

                </td>
                <td style="width:1px; white-space: nowrap;vertical-align: middle;">

                    <div xclass="form-group">
                      @if ( $document->editable )
                      <div class="input-group">

                        <span class="input-group-addon input-sm"><strong>%</strong></span>

                        <input name="document_discount_percent" id="document_discount_percent" class="input-update-document-total form-control input-sm col-xs-2" type="text" size="5" maxlength="10" style="width: auto;" value="{{ $document->as_percent('document_discount_percent') }}" onclick="this.select()" xonchange="add_discount_to_document($('#document_id').val());">

                        <span class="input-group-btn">
                          <button class="update-document-total btn btn-sm btn-lightblue" type="button" title="{{l('Apply', [], 'layouts')}}" xonclick="add_discount_to_document($('#document_id').val());">
                              <span class="fa fa-calculator"></span>
                          </button>
                        </span>

                      </div>
                      @else
                        {{ $document->as_percent('document_discount_percent') }}
                      @endif
                    </div>

                </td>
                <td style="width:1px; white-space: nowrap;vertical-align: middle;">

                        {{ $document->as_percent('document_ppd_percent') }}

                </td>
                <td style="vertical-align: middle;">{{ $document->as_price('total_currency_tax_excl', $document->currency) }}</td>
                <td style="vertical-align: middle;">{{ $document->as_priceable($document->total_currency_tax_incl - $document->total_currency_tax_excl) }}</td>
                <td class="text-right lead" style="vertical-align: middle;"><strong>{{ $document->as_price('total_currency_tax_incl') }}</strong></td>
            </tr>

@if ( $document->currency_conversion_rate != 1.0 )
            <tr>
                <td class="text-center">
                    <span class="badge" style="background-color: #3a87ad;">{{ AbiContext::getContext()->currency->iso_code }}</span>
                </td>
                <td style="vertical-align: middle;">
<!--
                    @if( AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ $document->as_price('total_lines_tax_incl') }}
                    @else
                    {{ $document->as_price('total_lines_tax_excl') }}
                    @endif
-->
                </td>
                <td>

                </td>
                <td style="vertical-align: middle;">{{ $document->as_price('total_tax_excl', $document->currency) }}</td>
                <td style="vertical-align: middle;">{{ $document->as_priceable($document->total_tax_incl - $document->total_tax_excl) }}</td>
                <td class="text-right lead" style="vertical-align: middle;"><strong>{{ $document->as_price('total_tax_incl') }}</strong></td>
            </tr>
@endif

        </tbody>
    </table>

       </div>
    </div>


@include($view_path.'._panel_document_total_breakdown')
