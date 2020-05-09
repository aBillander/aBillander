
    <div xclass="page-header">
        <h3 class="alert-success">
            <span xstyle="color: #dd4814;"> ** Nuevo {{ l('Total Breakdown') }} ** </span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} -->
             {{-- include getSubtotals functions in billable trait --}}
        </h3>        
    </div>

    <div id="div_document_total_breakdown_rebuild">
       <div class="table-responsive">

@php

    $alltaxes = \App\Tax::get()->sortByDesc('percent');
    $alltax_rules = \App\TaxRule::get();

    $documentlines = $document->documentlines;
    $taxlines = $document->documentlinetaxes;

    $reduction =  (1.0 - $document->document_discount_percent/100.0) * (1.0 - $document->document_ppd_percent/100.0);

    $currency = $document->currency;

    $B = array();
    $RB = array();
    $D = array();
    $RD = array();

    $TB = array();
    $RTB = array();
    $T = array();
    $TT = array();
    $RT = array();
    $TEB = array();
    $RTEB = array();
    $TE = array();
    $TET = array();
    $RTE = array();

    $TOTAL_TAX_EXCL = 0.0;
    $TOTAL_TAX      = 0.0;
    $TOTAL_TAX_RE   = 0.0;

@endphp

    <table id="document_total_breakdown" class="table table-hover">
        <thead>
            <tr>
          <th>Tipo</th>
          <th>Importe</th>
          <!-- th>Descuento</th -->
          <th>Base</th>
          <th>IVA</th>
          <th>Tipo</th>
          <th>Base</th>
          <th>RE</th>
            </tr>
        </thead>


      <tbody>

@foreach( $alltaxes as $alltax )

@php

    $lines = $taxlines->where('tax_id', $alltax->id);

    if ( $lines->count() == 0 ) continue;

    $B[0] = $documentlines->where('tax_id', $alltax->id)->sum('total_tax_excl');
    $B[1] = $B[0] * (1.0 - $document->document_discount_percent/100.0);
    $B[2] = $B[1] * (1.0 - $document->document_ppd_percent     /100.0);

    $RB[0] = $currency->round( $B[0] );
    $RB[1] = $currency->round( $B[1] );
    $RB[2] = $currency->round( $B[2] );

    $RD[1] = $RB[1] - $RB[0];
    $RD[2] = $RB[2] - $RB[1];

    // Sales Tax

    $TB[0] = $lines->where('tax_rule_type', 'sales')->sum('taxable_base'  );   // Base
    $TB[1] = $TB[0] * (1.0 - $document->document_discount_percent/100.0);
    $TB[2] = $TB[1] * (1.0 - $document->document_ppd_percent     /100.0);

    $RTB[0] = $currency->round( $TB[0] );
    $RTB[1] = $currency->round( $TB[1] );
    $RTB[2] = $currency->round( $TB[2] );

    $T[0] = $lines->where('tax_rule_type', 'sales')->sum('total_line_tax');   // Sales Tax
    $T[1] = $T[0] * (1.0 - $document->document_discount_percent/100.0);
    $T[2] = $T[1] * (1.0 - $document->document_ppd_percent     /100.0);

    $TT[0] = $TB[0] + $lines->where('tax_rule_type', 'sales')->sum('total_line_tax');   // Sales Base + Tax
    $TT[1] = $TT[0] * (1.0 - $document->document_discount_percent/100.0);
    $TT[2] = $TT[1] * (1.0 - $document->document_ppd_percent     /100.0);

    $RT[0] = $currency->round( $TT[0] - $TB[0]);
    $RT[1] = $currency->round( $TT[1] - $TB[1] );
    $RT[2] = $currency->round( $TT[2] - $TB[2] );

    // Sales Ecualization Tax

    $TEB[0] = $lines->where('tax_rule_type', 'sales_equalization')->sum('taxable_base'  );   // Base
    $TEB[1] = $TEB[0] * (1.0 - $document->document_discount_percent/100.0);
    $TEB[2] = $TEB[1] * (1.0 - $document->document_ppd_percent     /100.0);

    $RTEB[0] = $currency->round( $TEB[0] );
    $RTEB[1] = $currency->round( $TEB[1] );
    $RTEB[2] = $currency->round( $TEB[2] );

    $TE[0] = $lines->where('tax_rule_type', 'sales_equalization')->sum('total_line_tax');   // Sales Ecualization Tax
    $TE[1] = $TE[0] * (1.0 - $document->document_discount_percent/100.0);
    $TE[2] = $TE[1] * (1.0 - $document->document_ppd_percent     /100.0);

    $TET[0] = $TEB[0] + $lines->where('tax_rule_type', 'sales_equalization')->sum('total_line_tax');   // Sales Ecualization Base + Tax
    $TET[1] = $TET[0] * (1.0 - $document->document_discount_percent/100.0);
    $TET[2] = $TET[1] * (1.0 - $document->document_ppd_percent     /100.0);

    $RTE[0] = $currency->round( $TET[0] - $TEB[0] );
    $RTE[1] = $currency->round( $TET[1] - $TEB[1] );
    $RTE[2] = $currency->round( $TET[2] - $TEB[2] );

    // Totals
    $TOTAL_TAX_EXCL += $RB[2];
    $TOTAL_TAX      += $RT[2];
    $TOTAL_TAX_RE   += $RTE[2];


@endphp
        <tr>
          <td>{{ $alltax->as_percentable( $alltax->percent ) }}</td>
          <td>{{ $alltax->as_priceable( $RB[2] ) }}</td>
          <!-- td>{{ '' }}</td -->
          <td>{{ $alltax->as_priceable( $RTB[2] ) }}</td>
          <td>{{ $alltax->as_priceable( $RT[2]  ) }}</td>

          <td>{{ $alltax->as_percentable( $alltax->equalization_percent ) ?: '' }}</td>

          <td>{{ $alltax->as_priceable( $RTEB[2] ) }}</td>
          <td>{{ $alltax->as_priceable( $RTE[2] ) }}</td>
        </tr>
{{--
@php

// abi_r($total); die();

// $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
// $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalizarion')->first();

@endphp
--}}
@endforeach

      </tbody>

    </table>

@php

// abi_r($taxlines); 

@endphp

       </div>
    </div>



    <div id="none">
       <div class="table-responsive">

    <table id="document_total_breakdown" class="table table-hover">
        <thead>
            <tr>
          <th>Total Base</th>
          <th>Total IVA</th>
          <th>Total RE</th>
          <th>Total Documento</th>
            </tr>
        </thead>


      <tbody>

        <tr>
          <td>{{ $alltax->as_priceable( $TOTAL_TAX_EXCL ) }}</td>
          <td>{{ $alltax->as_priceable( $TOTAL_TAX ) }}</td>
          <td>{{ $alltax->as_priceable( $TOTAL_TAX_RE ) }}</td>
          <td class="alert-info">{{ $alltax->as_priceable( $TOTAL_TAX_EXCL + $TOTAL_TAX + $TOTAL_TAX_RE ) }}</td>
        </tr>

      </tbody>

    </table>

       </div>
    </div>
