
    <div xclass="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Total Breakdown') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} -->
             {{-- include getSubtotals functions in billable trait --}}
        </h3>        
    </div>

    <div id="div_document_total_breakdown">
       <div class="table-responsive">

@php

    $alltaxes = \App\Models\Tax::get()->sortByDesc('percent');
    $alltax_rules = \App\Models\TaxRule::get();

    $totals = $document->totals();

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

    if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) continue;

    $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
    $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalization')->first();

@endphp
        <tr>
          <td>{{ $alltax->as_percentable( $alltax->percent ) }}</td>
          <td>{{ $alltax->as_priceable($total['net_amount']) }}</td>
          <!-- td>{{ '' }}</td -->
          <td>{{ $alltax->as_priceable($iva->taxable_base) }}</td>
          <td>{{ $alltax->as_priceable($iva->total_line_tax) }}</td>
          <td>{{ optional($re)->as_percent('percent') ?? '' }}</td>
          <td>{{ optional($re)->as_price('taxable_base') ?? '' }}</td>
          <td>{{ optional($re)->as_price('total_line_tax') ?? '' }}</td>
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

       </div>
    </div>
