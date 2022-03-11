
	<style type="text/css">
	.tax-summary-wrapper {
		/* padding-left: 2cm; */
		xmargin-top: 15mm;
		margin-top: 7mm;

		xborder: 0.3px solid black;
		page-break-inside: avoid !important;
	}
	.tax-summary {
		width: 100%;
	}
	.tax-summary td,
	.tax-summary th {
		text-align: right;
	}
	</style>

@php

$alltaxes = \App\Models\Tax::get()->sortByDesc('percent');
$alltax_rules = \App\Models\TaxRule::get();

$totals = $document->totals();

@endphp

	<div class="tax-summary-wrapper" style="margin-top: 2mm !important;">
		<table class="order-details tax-summary print-friendly" style="margin-bottom: 2mm !important;;">
			<tbody>
				<tr>
					<th>Tipo</th>
					<th>Importe</th>
					<th>Descuento {{ $document->document_discount_percent != 0 ? '('.$document->as_percentable($document->document_discount_percent).'%)' : '' }}</th>
					<th>Pronto Pago {{ $document->document_ppd_percent != 0 ? '('.$document->as_percentable($document->document_ppd_percent).'%)' : ''  }}</th>
					<th>Base</th>
					<th>{!! AbiConfiguration::get('CUSTOMER_INVOICE_TAX_LABEL') !!}</th>
					<!-- th>Tipo</th -->
					<th>Base</th>
					<th>RE</th>
				</tr>

@foreach( $alltaxes as $alltax )

	@if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) @continue
	@endif

@php



$iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
$re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalization')->first();

@endphp
				<tr>
					<td>{{ $alltax->as_percentable( $alltax->percent ) }}</td>
					<td>{{ $alltax->as_priceable($total['gross_amount']) }}</td>

					<td>{{ $total['discount_amount'] != 0 ? $alltax->as_priceable($total['discount_amount']) : '' }}</td>
					<td>{{ $total['ppd_amount']      != 0 ? $alltax->as_priceable($total['ppd_amount'])      : '' }}</td>
					
					<td>{{ $alltax->as_priceable($iva->taxable_base) }}</td>
					<td>{{ $alltax->as_priceable($iva->total_line_tax) }}</td>
					<!-- td>{{ optional($re)->as_percent('percent') ?? '' }}</td -->
					<td>{{ optional($re)->as_price('taxable_base') ?? '' }}</td>
					<td>{{ optional($re)->as_price('total_line_tax') ?? '' }}</td>
				</tr>

@php

// abi_r($total); die();

// $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
// $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalizarion')->first();

@endphp
@endforeach

			</tbody>
		</table>
	</div>
	