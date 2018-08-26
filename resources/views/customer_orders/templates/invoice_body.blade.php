


<table class="head container">

	<tr>

		<td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="img-rounded" height="{{ '60' }}" src="{{ URL::to( \App\Company::$company_path . $img ) }}">

        @endif

		</td>

		<td class="shop-info">

			<div class="shop-name"><h3>{{ $company->name_fiscal }}</h3></div>

			<div class="shop-address">
                        {{ $company->address->address1 }} {{ $company->address->address2 }}<br />
                        {{ $company->address->postcode }} {{ $company->address->city }} ({{ $company->address->state->name }})<br />
                        CIF: {{ $company->identification }}<br />
                        {{-- $company->address->phone }} / {{ $company->address->email --}}<br />
				
			</div>

		</td>

	</tr>

</table>



<h1 class="document-type-label"> FACTURA </h1>



<table class="order-data-addresses">

	<tr>

		<td class="address billing-address">

			<!-- <h3>Billing Address:</h3> -->

            {{ $document->invoicingaddress->name_commercial }}<br />

			@if ( $document->invoicingaddress->contact_name != $document->invoicingaddress->name_commercial )

            	{{ $document->invoicingaddress->contact_name }}<br />

			@endif

            {{ $document->invoicingaddress->address1 }} {{ $document->invoicingaddress->address2 }}<br />

            {{ $document->invoicingaddress->postcode}} {{ $document->invoicingaddress->city }}<br />

            {{ $document->invoicingaddress->state->name }}


			@if ( $document->invoicingaddress->mail )

				<div class="billing-email">{{ $document->invoicingaddress->mail }}</div>

			@endif

			@if ( $document->customer->identification )
            
            <div class="cif">CIF/NIF: {{ $document->customer->identification }}</div>

			@endif

			@if ( $document->invoicingaddress->phone )

				<div class="billing-phone">Tel. {{ $document->invoicingaddress->phone }}</div>

			@endif
			
		</td>

		<td class="address shipping-address">
{{--
			<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_shipping_address']) && $wpo_wcpdf->ships_to_different_address()) { ?>

			<h3><?php _e( 'Ship To:', 'wpo_wcpdf' ); ?></h3>

			<?php $wpo_wcpdf->shipping_address(); ?>

			<?php } ?>
--}}
		</td>

		<td class="order-data">

			<table>

				<tr class="invoice-number">

					<th>Factura nº:</th>

					<td>{{ $document->id }}</td>

				</tr>

				<tr class="invoice-date">

					<th>Fecha de la Factura:</th>

					<td>{{ abi_date_short($document->document_date) }}</td>

				</tr>

				<tr class="order-number">

					<th>Albarán / Pedido nº:</th>

					<td>{{ $document->document_reference }}</td>

				</tr>

				<tr class="order-date">

					<th>Fecha del Albarán:</th>

					<td>{{ abi_date_short($document->document_date) }}</td>

				</tr>

				<tr class="payment-method">

					<th>Forma de Pago:</th>

					<td>{{ $document->paymentmethod->name }}</td>

				</tr>

			</table>			

		</td>

	</tr>

</table>




            
@if ($document->documentlines->count()>0)  

<table class="order-details">

	<thead>

		<tr>

			<th class="sku first-column">
				<span>Referencia</span>
			</th>
			<th class="description">
				<span>Producto</span>
			</th>
			<th  class="price">
				<span>Precio</span>
			</th>
			<th class="quantity">
				<span>Cantidad</span>
			</th>
			<th class="total last-column">
				<span>Total</span>
			</th>
		</tr>

	</thead>

	<tbody>

                @foreach ($document->documentlines as $line)

			    @if ( 
			    			( $line->line_type != 'product' ) &&
			    			( $line->line_type != 'service' )
			    )
			        @continue
			    @endif

		<tr class="3655">
			<td class="sku first-column">
				<span>{{ $line->reference }}</span>
			</td>
			<td class="description">
				<span>
					<span class="item-name">{{ $line->name }}</span>
					<span class="item-combination-options"></span>
				</span>
			</td>
			<td class="price">
				<span>
					<span class="abi-Price-amount amount">2,38
						<span class="abi-Price-currencySymbol">€</span>
					</span>
				</span>
			</td>
			<td class="quantity">
				<span>{{ $line->as_quantity('quantity') }}
				</span>
			</td>
			<td class="total last-column">
				<span>
					<span class="abi-Price-amount amount">7,14
						<span class="abi-Price-currencySymbol">€</span>
					</span>
				</span>
			</td>
		</tr>

                @endforeach

	</tbody>

</table>

@endif



<table class="notes-totals">

	<tbody>

		<tr class="no-borders">

			<td class="no-borders">

				<div class="customer-notes">
{{--
					< ? php if ( $wpo_wcpdf->get_shipping_notes() ) : ?>

						<h3>< ? php _e( 'Customer Notes', 'wpo_wcpdf' ); ?></h3>

						< ? php $wpo_wcpdf->shipping_notes(); ?>

					< ? php endif; ?>
--}}
				</div>	

			</td>

			<td class="no-borders totals-cell" style="width:40%">

				<table class="totals">

					<tfoot>

						
								<tr class="subtotal">

									<th class="description"><span>Total productos (sin impuestos)</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">58,10<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="tax-line IVA-1">

									<th class="description"><span>IVA 10,000%</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">5,81<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="tax-line R.E.-2">

									<th class="description"><span>R.E. 1,400%</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">0,81<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="subtotal">

									<th class="description"><span>Total productos (impuestos incluidos)</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">64,72<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="shipping">

									<th class="description"><span>Gastos de envío (sin impuestos)</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">2,47<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="shipping">

									<th class="description"><span>Total gastos de envío (impuestos incluidos)</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">2,99<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="total grand-total">

									<th class="description"><span>Total (impuestos incluidos)</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">67,71<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
					</tfoot>

				</table>

				{{-- Otra versión for NOT professionals --}}

				<table class="totals">

					<tfoot>

						
								<tr class="subtotal">

									<th class="description"><span>Total productos (impuestos incluidos)</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">12,93<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="shipping">

									<th class="description"><span>Gastos de envío (impuestos incluidos)</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">3,99<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
								<tr class="total grand-total">

									<th class="description"><span>Total (impuestos incluidos) </span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">16,92<span class="abi-Price-currencySymbol">€</span></span></span></td>

								</tr>

								
					</tfoot>

				</table>

			</td>

		</tr>

	</tbody>

</table>


				{{-- Otra versión for NOT professionals --}}


	<style type="text/css">
	.tax-summary-wrapper {
		/* padding-left: 2cm; */
	}
	.tax-summary {
		width: 100%;
	}
	.tax-summary td,
	.tax-summary th {
		text-align: right;
	}
	</style>
	<div class="tax-summary-wrapper">
		<table class="tax-summary">
			<tbody><tr>
				<th>Desglose impuestos</th>
				<th>Porcentaje impuesto</th>
				<th>Total sin impuestos</th>
				<th style="width:16%">Total impuestos</th>
				<th style="width:25%">Total impuestos incluidos</th>
			</tr>
						<tr>
				<td><span>Articulos</span></td>
				<td><span>IVA 10,000%</span></td>
				<td><span><span class="abi-Price-amount amount">11,75<span class="abi-Price-currencySymbol">€</span></span></span></td>
				<td><span><span class="abi-Price-amount amount">1,18<span class="abi-Price-currencySymbol">€</span></span></span></td>
				<td><span><span class="abi-Price-amount amount">12,93<span class="abi-Price-currencySymbol">€</span></span></span></td>
			</tr>
						<tr>
				<td><span>Transportista</span></td>
				<td><span>IVA 21,000%</span></td>
				<td><span><span class="abi-Price-amount amount">11,75<span class="abi-Price-currencySymbol">€</span></span></span></td>
				<td><span><span class="abi-Price-amount amount">1,18<span class="abi-Price-currencySymbol">€</span></span></span></td>
				<td><span><span class="abi-Price-amount amount">12,93<span class="abi-Price-currencySymbol">€</span></span></span></td>
			</tr>
								</tbody></table>
	</div>
	



<div id="footer">
{{--
	< ? php $wpo_wcpdf->footer(); ?>
--}}
</div><!-- #letter-footer -->