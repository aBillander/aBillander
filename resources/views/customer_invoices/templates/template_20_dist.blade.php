<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="dompdf.view" content="XYZ,0,0,1" />
	<!-- http://stackoverflow.com/questions/10844990/pdf-generation-using-dompdf -->
	
	<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="{{ url('invoices/css/invoice.css') }}">
	<link rel="stylesheet" type="text/css" href="http://localhost/aBillander5/public/invoices/css/invoice.css">
</head>
<body>
	<div id="invoice">
		<table>
			<tr>
				<td class="col-md-8">
					<span class="h1">
					@if (isset($logo->name) ?? 1)
					<img src="{{ URL::to('uploads/' . '1425983247.png' ) }}">
					@endif
					</span>
				</td>
				<td class="col-md-4">
					<span class="h1">Factura</span>
					
					<table class="border">
						<tr>
							<th class="col-md-6">Núm.:</th>
							<th class="col-md-6">Fecha:</th>
						</tr>

						<tr>						
							<td class="text-center">@if( $cinvoice->document_id > 0 )
														{{ $cinvoice->document_prefix }} {{ $cinvoice->document_id }}
													@else
														<span class="small">BORRADOR</span>
													@endif</td>
							<td class="text-center">{{ $cinvoice->document_date }}</td>
						</tr>
					</table>					
				</td>
			</tr>
		</table>
		
		<table>
			<tr>
				<td class="col-md-6">
					<p class="text-left"><span class="h2">{{ $company->name_fiscal }}</span></p>
					<p class="details">NIF/CIF: {{ $company->identification }}</p>
					<p class="details">{{ $company->address->address1 }} {{ $company->address->address2 }}</p>
					<p class="details">{{ $company->address->city }}, {{ $company->address->postcode }} {{ $company->address->state->name }}, {{ $company->address->country->name }}</p>
					<p class="details"></p>
					<p class="details">{{ $company->address->phone }} / {{ $company->address->email }}</p>
				</td>

				<td class="col-md-6">			
					<p class="text-left background-th"><span class="h2">Cliente: </span> <span class="h4">{{ $cinvoice->customer->name_fiscal }}</span></p>
					<p class="details">NIF/CIF: {{ $cinvoice->customer->identification }}</p>
					<p class="details">{{ $cinvoice->invoicingAddress->address1 }} {{ $cinvoice->invoicingAddress->address2 }}</p>
					<p class="details">{{ $cinvoice->invoicingAddress->city }}, {{ $cinvoice->invoicingAddress->postcode}} {{ $cinvoice->invoicingAddress->state->name }}, {{ $cinvoice->invoicingAddress->country->name }}</p>
					<p class="details">{{-- $cinvoice->invoicingAddress->firstname } } { { $cinvoice->invoicingAddress->lastname --}}</p>
					<p class="details">{{ $cinvoice->invoicingAddress->phone }} &nbsp; {{ $cinvoice->invoicingAddress->mail }}</p>
				</td>
			</tr>
		</table>
			
		@if ($cinvoice->customerInvoiceLines->count()>0)  

		<table class="border table-striped top20">
			<tr>
				<th class="crt">Ref.</th>
				<th class="product">Item</th>
				<th class="qty">Cantidad</th>
				<th class="small">Precio</th>
				<th class="qty">Descuento</th>
				<th class="qty">Impuesto</th>
				<th class="small">Total</th>
			</tr>
			
			@foreach ($cinvoice->customerInvoiceLines as $line)

				<tr>
					<td>
						{{ $line->reference }}
					</td>
					
					<td>
						{{ $line->name }}
					</td>
					
					<td class="text-center">
						{{ $line->quantity }}
					</td>
					
					<td class="text-right">
						{{ $line->unit_final_price }}
					</td>
					
					<td class="text-center">
						{{ $line->discount_percent }} %
					</td>
					
					<td class="text-right">
						{{ \App\Models\Tax::find($line->tax_id)->percent }} %
					</td>
					
					<td class="text-right">
						{{ $line->total_tax_incl }}
					</td>							
				</tr>
				
				@if ($line->notes)
				<tr>
					<td colspan="7">
						{{ $line->notes }}
					</td>
				</tr>
				@endif
									
			@endforeach	

			
			<tr class="bg-white">
				<td colspan="4" class="text-center">
					<div class="top20">Gracias por su compra!</div>
				</td>
				
				<td colspan="3" class="total">
					<div class="top10">Sub-Total: 
						{{ $cinvoice->total_products_tax_excl }}
					</div>
					
					<div>Impuestos: 
						{{ $cinvoice->total_products_tax_incl - $cinvoice->total_products_tax_excl }}
					</div>
			{{--
					@if ( $discountItems != 0 )
						<div>{{ trans('invoice.discount') }}: 
							- {{ $invoice->position == 1 ? $invoice->currency : '' }} {{ number_format($discountItems, 2, '.', '') }} {{ $invoice->position == 2 ? $invoice->currency : '' }}
						</div>
					@endif
			--}}		
					@if ( $cinvoice->total_discounts_tax_incl != 0 )
						<div>Descuento ({{ $cinvoice->document_discount }}%): 
							{{ $cinvoice->total_discounts_tax_incl }}
						</div>
					@endif
					
					<div class="h4 top10">
						<strong>TOTAL: 
							{{ $cinvoice->total_tax_incl }}
						</strong>
					</div>
				</td>
			</tr>			
		</table>

		@endif

	</div>
	
	@if (isset($cinvoice->text))
		<div class="footer">
			{{ $cinvoice->text }}
		</div>	
	@endif
	
</body>
</html>