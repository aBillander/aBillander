


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
                        {{ $company->address->phone }} / {{ $company->address->email }}<br />
				
			</div>

		</td>

	</tr>

</table>



<h1 class="document-type-label"> FACTURA </h1>

<table class="order-data-addresses">

	<tr>

		<td class="address billing-address">

			<h3>Dirección de Facturación:</h3>

            {{ $document->invoicingaddress->name_fiscal }}<br />

			@if ( $document->invoicingaddress->contact_name != $document->invoicingaddress->name_fiscal )

            	{{ $document->invoicingaddress->contact_name }}<br />

			@endif

            {{ $document->invoicingaddress->address1 }} {{ $document->invoicingaddress->address2 }}<br />

            {{ $document->invoicingaddress->postcode}} {{ $document->invoicingaddress->city }} 

            {{ $document->invoicingaddress->state->name }}


			@if ( $document->invoicingaddress->mail )

				<div class="billing-email">{{ $document->invoicingaddress->mail }}</div>

			@endif
            
            <div class="cif">CIF/NIF: {{ $document->customer->identification }}</div>

			@if ( $document->invoicingaddress->phone )

				<div class="billing-phone">Tel. {{ $document->invoicingaddress->phone }}</div>

			@endif
			
		</td>

		<td xclass="address shipping-address">
{{--
			< ? php if ( isset($wpo_wcpdf->settings->template_settings['packing_slip_billing_address']) && $wpo_wcpdf->ships_to_different_address() ) { ?>

			<h3>< ? php _e( 'Billing Address:', 'wpo_wcpdf' ); ?></h3>

			< ? php $wpo_wcpdf->billing_address(); ?>

			< ? php } ?>
--}}
		</td>

		<td class="order-data">

			<table>

				<tr class="order-number">

					<th>Factura nº:</th>

					<td>{{ $document->document_reference }}</td>

				</tr>

				<tr class="order-date">

					<th>Fecha de la Factura:</th>

					<td>{{ abi_date_short($document->document_date) }}</td>

				</tr>

				@if ( $document->shippingmethod && 0 )

				<tr class="shipping-method">

					<th>Método de Envío:</th>

					<td>{{ $document->shippingmethod->name }}</td>

				</tr>

				@endif

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
			<th class="quantity last-column">
				<span>Cantidad</span>
			</th>
			<th class="discount">
				<span>Descuento</span>
			</th>
			<th class="total last-column">
				<span>Total</span>
			</th>
		</tr>

	</thead>

	<tbody>

                @foreach ($document->documentlines->sortBy('line_type') as $line)

			    @if ( 
			    			( $line->line_type != 'product'  ) &&
			    			( $line->line_type != 'service'  ) &&
			    			( $line->line_type != 'shipping' )
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
					<span class="abi-Price-amount amount">{{ $line->as_price('unit_final_price_tax_inc') }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="quantity"><span>{{ $line->as_quantity('quantity') }}</span>
			</td>
			<td class="discount">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_percent('discount_percent') }}
							<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_price('total_tax_incl') }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
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

@if ($document->notes_to_customer)

						<h3>Notas:</h3>

						{{ $document->notes_to_customer }}
@endif
				</div>	

			</td>

			<td class="no-borders totals-cell" style="width:40%">

				<table class="totals">

					<tfoot>
{{--
						< ? php

						$totals = $wpo_wcpdf_templates->get_totals( $wpo_wcpdf->export->template_type );

						if( sizeof( $totals ) > 0 ) {

							foreach( $totals as $total_key => $total_data ) {

								?>

								<tr class="< ? php echo $total_data['class']; ?>">

									<th class="description"><span>< ? php echo $total_data['label']; ?></span></th>

									<td class="price"><span class="totals-price">< ? php echo $total_data['value']; ?></span></td>

								</tr>

								< ? php

							}

						}

						?>
--}}

								
								<tr class="total grand-total">

									<th class="description"><span>Total a pagar</span></th>
									<!-- p>The euro character:  €  &#0128;  &euro;  &#8364;   :: Not work: &#0128;  </p -->

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">{{ $document->as_price('total_currency_tax_incl') }}<span class="abi-Price-currencySymbol">{{ $document->currency->sign_printable }}</span></span></span></td>

								</tr>
					</tfoot>

				</table>

			</td>

		</tr>

	</tbody>

</table>


{{--
https://github.com/dompdf/dompdf/issues/1506
http://eclecticgeek.com/dompdf/debug.php?identifier=toc-v0.6.x

https://github.com/dompdf/dompdf/issues/1636
https://codepen.io/Bhupinderkumar/pen/gKzKGw

<div id="pageCounter">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
</div>
<div id="pageNumbers">
  <div class="page-number"></div>
  <div class="page-number"></div>
  <div class="page-number"></div>
  <div class="page-number"></div>
</div>

<style type="text/css">
#pageCounter { 
	counter-reset: pageTotal; 
} 
#pageCounter span { 
	counter-increment: pageTotal; 
} 
#pageNumbers { 
	counter-reset: currentPage; 
} 
#pageNumbers div:before 
{ 
	counter-increment: currentPage; 
	content: "Page " counter(currentPage) " of "; 
} 
#pageNumbers div:after { 
	content: counter(pageTotal); 
}
</style>
--}}


@include('templates::customer_invoices.default.totals')


{{--

	<style type="text/css">
	.tax-summary-wrapper {
		/* padding-left: 2cm; */
		margin-top: 15mm;
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
		<table class="order-details tax-summary">
			<tbody>
				<tr>
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
			</tbody>
		</table>
	</div>
	
--}}




<div id="footer">
{{--
	< ? php $wpo_wcpdf->footer(); ?>
--}}

{{ $company->name_fiscal }} - .

	<!-- p><span class="pagenum"></span> / <span class="pagecount">{PAGE_COUNT}</span></p -->


<script type="text/php">

    if ( isset($pdf) ) {

        // $pdf->page_text(30, ($pdf->get_height() - 26.89), "Impreso el: " . date('d M Y H:i:s'), null, 10);
        
        if ( $PAGE_COUNT > 1 || 1 )
        {
               $pdf->page_text(($pdf->get_width() - 84), ($pdf->get_height() - 26.89), "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10);
        }


    }

</script>


</div><!-- #letter-footer -->

{{-- 

https://github.com/dompdf

view-source:https://dompdf.net/test/print_header_footer.html

https://groups.google.com/forum/#!forum/dompdf

https://groups.google.com/forum/#!topic/dompdf/X9sl6KLYimM

https://github.com/samuelterra22/laravel-report-generator/blob/master/src/views/general-pdf-template.blade.php

--}}
