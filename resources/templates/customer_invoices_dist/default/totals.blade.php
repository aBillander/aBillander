
	<style type="text/css">
	.tax-summary-wrapper {
		/* padding-left: 2cm; */
		margin-top: 15mm;

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

$alltaxes = \App\Tax::get()->sortByDesc('percent');
$alltax_rules = \App\TaxRule::get();

$totals = $document->totals();

@endphp

	<div class="tax-summary-wrapper">
		<table class="order-details tax-summary">
			<tbody>
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

@foreach( $alltaxes as $alltax )

	@if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) @continue
	@endif

@php



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

@php

// abi_r($total); die();

// $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
// $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalizarion')->first();

@endphp
@endforeach

			</tbody>
		</table>
	</div>
	