
	<style type="text/css">
	.payments-wrapper {
		/* padding-left: 2cm; */
		margin-top: 15mm;

		xborder: 0.3px solid black;
		page-break-inside: avoid !important;
	}
	.payments {
		width: 100%;
	}
	.payments td,
	.payments th {
		text-align: right;
	}
	</style>

@php

$alltaxes = \App\Tax::get()->sortByDesc('percent');
$alltax_rules = \App\TaxRule::get();

$totals = $document->totals();

@endphp

	<div class="payments-wrapper">

{{-- Payments --}}

<table class="notes-totals print-friendly">

	<tbody>

		<tr class="no-borders">

			<td class="no-borders">


		<table class="order-details payments">
			<thead>
				<tr>
					<th style="border: 1px #ccc solid">Concepto</th>
					<th style="border: 1px #ccc solid">Vencimiento</th>
					<th style="border: 1px #ccc solid">Importe</th>
				</tr>
			</thead>
			<tbody>

@if ($document->payments->count())


	@foreach ($document->payments->sortBy(function ($item, $key) {
											    return $item->getAttributes()['due_date'];
											}) as $payment)

				<tr>
					<td>{{ $payment->name }}</td>
					<td>{{ $payment->due_date }}</td>
					<td>{{ abi_money_amount($payment->amount, $document->currency) }}</td>
				</tr>

@endforeach

@endif
			</tbody>
		</table>


			</td>

			<td class="no-borders totals-cell" style="width:10%">


			</td>

			<td class="no-borders totals-cell" style="width:50%">


@if( $document->paymentmethod && !$document->paymentmethod->payment_is_cash && $document->customer->bankaccount)

{{-- Bank Account --}}

	<div xclass="tax-summary-wrapper">
		<table class="order-details tax-summary">
			<thead>
			</thead>
			<tbody>
				<tr>
					<th class="description" style="background-color: #f5f5f5;">Cuenta de cargo</th>
				</tr>
				<tr>
					<td class="description">

						{{ $document->customer->bankaccount->bank_name }}<br />

						{{ $document->customer->bankaccount->ccc_entidad }} &nbsp; {{ $document->customer->bankaccount->ccc_oficina }} &nbsp; {{ $document->customer->bankaccount->ccc_control }} &nbsp; {{ $document->customer->bankaccount->ccc_cuenta }} 



					</td>
				</tr>
			</tbody>
		</table>
	</div>

@endif

			</td>

		</tr>

	</tbody>

</table>






	</div>
	