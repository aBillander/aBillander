
    <div xclass="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Production Order Tools') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} -->
             {{-- include getSubtotals functions in billable trait --}}
        </h3>        
    </div>

<div class="panel-body" id="div_tool_requirements">
   <div class="table-responsive">


@if ($document->tool_lines->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <!-- th>{{l('ID', [], 'layouts')}}</th -->
      <th>{{l('Tool ID')}}</th>
      <th>{{l('Tool Reference')}}</th>
      <th>{{l('Tool Name')}}</th>
      <th>{{l('Quantity')}}</th>
      <th>{{l('Location')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($document->tool_lines as $line)

    <tr>
      <td>{{ $line->tool_id }}</td>
      <td>{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td>{{ number_format( $line->quantity, 0, '', '' ) }}</td>
      <td>{{ $line->location }}</td>

           <td class="text-right" style="width:1px; white-space: nowrap;">
{{--
                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('productionsheets/' . $sheet->id . '/show') }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('sheets/' . $sheet->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('sheets/' . $sheet->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Production Sheets') }} :: ({{$sheet->id}}) {{{ $sheet->name }}}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->
--}}
            </td>
    </tr>
  @endforeach
    </tbody>
</table>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>


</div><!-- div class="panel-body" -->


{{--
    <div id="div_document_total  hide ">
       <div class="table-responsive">

    <table id="document_total" class="table table-hover">
        <thead>
            <tr>
               <th class="text-center">{{ l('Lines') }}</th>
               <th> </th>
               <th class="text-left">

                    @if( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
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

               <th class="text-right">{{l('Weight')}} (<span class="text-success">{{ optional($weight_unit)->sign }}</span>)</th>
               <th class="text-right">{{l('Volume')}} (<span class="text-success">{{ optional($volume_unit)->sign }}</span>)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="text-center">
                    {{ $document->nbr_lines }}
                </td>
                <td class="text-center">
                    <span class="badge" style="background-color: #3a87ad;">{{ $document->currency->iso_code }}</span>
                </td>
                <td style="vertical-align: middle;">

                    @if( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
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

                <td class="text-right lead" style="vertical-align: middle;">{{ $document->getWeight() }}</td>
                <td class="text-right lead" style="vertical-align: middle;">{{ $document->getVolume() }}</td>
            </tr>

@if ( $document->currency_conversion_rate != 1.0 )
            <tr>
                <td class="text-center">
                    <span class="badge" style="background-color: #3a87ad;">{{ \App\Context::getContext()->currency->iso_code }}</span>
                </td>
                <td style="vertical-align: middle;">
<!--
                    @if( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
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

                <td></td>
                <td></td>
            </tr>
@endif

        </tbody>
    </table>

       </div>
    </div>
--}}
{{--
        @include('production_orders.._panel_document_total_breakdown')
--}}
