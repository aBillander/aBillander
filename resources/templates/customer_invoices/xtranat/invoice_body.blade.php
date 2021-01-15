


<table class="head container" style="margin-top: -0.7cm !important">

	<tr>

		<td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="img-rounded" height="{{ '60' }}" src="{{ URL::to( \App\Company::imagesPath() . $img ) }}">

        @endif


		<div class="banner">

			&nbsp; {!! \App\Configuration::get('CUSTOMER_INVOICE_BANNER') !!}

		</div>

		</td>

		<td class="shop-info">
@php
	$name = $str = $company->name_fiscal;

	if( strlen( $str) > 33) {
	    $str = substr( $str, 0, 30);
	    $name = $str . '...';
	}
@endphp
			<div class="shop-name"><h3>{{ $name }}{{-- $company->name_fiscal --}}</h3></div>

			<div class="shop-address">
                        {{ $company->address->address1 }} {{ $company->address->address2 }}<br />
                        {{ $company->address->postcode }} {{ $company->address->city }} ({{ $company->address->state->name }})<br />
                        CIF: {{ $company->identification }} / Tel: {{ $company->address->phone }}<br />
                        {{ $company->address->email }}<br />
				
			</div>

		</td>

	</tr>

</table>


{{-- --} }
<h1 class="document-type-label">

	@switch($document->type)
	    @case( 'invoice' )
	         FACTURA 
	        @break

	    @case( 'corrective' )
	         FACTURA RECTIFICATIVA 
	        @break

	    @case( 'credit' )
	         NOTA DE ABONO 
	        @break

	    @case( 'deposit' )
	         ANTICIPO 
	        @break

	    @default
	         FACTURA 
	@endswitch

</h1>
{ {-- --}}

<table class="order-data-addresses">

	<tr>

		<td class="address billing-address" xstyle="background: red">

            <div class="shop-name"><h3>

			@if ( $document->customer->name_fiscal )

				{{ $document->customer->name_fiscal }}

			@else

            	{{ $document->invoicingaddress->contact_name }}

			@endif

			</h3></div>

			<strong>

			@if ( $document->customer->name_commercial )

				{{ $document->customer->name_commercial }}

			@else

            	{{ $document->invoicingaddress->contact_name }}

			@endif

			</strong><br />

            {{ $document->invoicingaddress->address1 }} {{ $document->invoicingaddress->address2 }}<br />

            {{ $document->invoicingaddress->postcode}} {{ $document->invoicingaddress->city }} <br />

            {{ $document->invoicingaddress->state->name }}


			@if ( $document->invoicingaddress->mail )

				<div class="billing-email">{{ $document->invoicingaddress->mail }}</div>

			@endif
            
            <div class="cif">CIF/NIF: {{ $document->customer->identification }} &nbsp; {{--<span xstyle="float: right; xmargin-left: 10mm">[{{ $document->customer->id }}]</span></div>

			<div class="billing-phone"> --}}
			@if ( $document->invoicingaddress->phone )

				Tel. {{ $document->invoicingaddress->phone }}

			@else

				Tel. {{ $document->customer->phone }}

			@endif

			@if ( 0 && $document->customer->reference_external )
				<span style="float: right; xmargin-left: 10mm">[{{ $document->customer->reference_external }}]</span>
			@endif
			</div>
			
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

					<th style="width: 45%; font-weight: bold;font-size: 16pt;vertical-align: baseline !important;">FACTURA:</th>

					<td style="width: 55%; font-weight: bold;font-size: 11pt;vertical-align: baseline !important;">{!! $document->document_reference ?? 'BORRADOR' !!}</td>

				</tr>

				<tr class="order-page">

					<th>Página:</th>

					<td> &nbsp; </td>

				</tr>

				<tr class="order-date">

					<th>Fecha:</th>

					<td><strong>{{ abi_date_short($document->document_date) }}</strong></td>

				</tr>

				@if ( $document->shippingmethod && 0 )

				<tr class="shipping-method">

					<th>Método de Envío:</th>

					<td>{{ $document->shippingmethod->name }}</td>

				</tr>

				@endif

				<tr class="order-number">

					<th> </th>

					<td> </td>

				</tr>

				<tr class="order-date">

					<th>Agente:</th>

					<td>{{ optional($document->salesrep)->name }}</td>

				</tr>

				<tr class="payment-method">

					<th>Forma de Pago:</th>

					<td>{{ optional($document->paymentmethod)->name }}</td>

				</tr>

			</table>			

		</td>

	</tr>

</table>




            
@if ($document->documentlines->count()>0)  

<table class="order-details" width="100%" style="border: 1px #ccc solid">

	<thead>

		<tr>

			<th class="sku first-column" width="10%" style="border: 1px #ccc solid">
				<span>Referencia</span>
			</th>
			<th class="description" width="41%" style="border: 1px #ccc solid">
				<span>Producto</span>
			</th>
			<th class="quantity last-column" width="8%" style="border: 1px #ccc solid">
				<span>Cantidad</span>
			</th>
			<th class="price" width="8%" style="border: 1px #ccc solid">
				<span>Precio Coste</span>
			</th>
			<th class="price" width="8%" style="border: 1px #ccc solid">
				<span>PVP Recomend.</span>
			</th>
			<th class="discount" width="6%" style="border: 1px #ccc solid">
				<span>Dto.</span>
			</th>
			<th class="tax" width="7%" style="border: 1px #ccc solid">
				<span>{!! \App\Configuration::get('CUSTOMER_INVOICE_TAX_LABEL') !!}</span>
			</th>
			<th class="total xlast-column" width="12%" style="border: 1px #ccc solid">
				<span>Total</span>
			</th>
		</tr>

	</thead>

	<tbody>

                @foreach ($document->documentlines->sortBy('line_sort_order') as $line)

			    @if ( 
			    			( $line->line_type != 'product'  ) &&
			    			( $line->line_type != 'service'  ) &&
			    			( $line->line_type != 'shipping' ) &&
			    			( $line->line_type != 'comment' )
			    )
			        @continue
			    @endif

@if( $line->line_type == 'comment' )

@if (substr($line->name, 0, 7) === 'Pedido:')
    @continue
@endif

		<tr class="3655">
			<td class="sku first-column">
				<span>{{-- $line->reference --}}</span>
			</td>
			<td class="description" colspan="7">
				<span>
					<span class="item-name"><strong>{{ $line->name }}</strong></span>
					<span class="item-combination-options"></span>
				</span>
			</td>
		</tr>
@else
	@if ( optional($line->product)->ecotax )

		@include('templates::customer_invoices.xtranat.line_rae')
	
	@else
		<tr class="3655">
			<td class="sku first-column">
				<span>{{ $line->reference }}</span>
			</td>
			<td class="description">
				<span>
					<span class="item-name">{{ $line->name }}</span>
					<span class="item-combination-options"></span>
				</span>
@if ( $line->package_measure_unit_id != $line->measure_unit_id && $line->pmu_label != '' )
				<br />
				<span class="abi-line-rule-label">{!! $line->pmu_label !!}
				</span>
@endif
@if ( $line->extra_quantity > 0 && $line->extra_quantity_label != '' )
				<br />
				<span class="abi-line-rule-label">{!! $line->extra_quantity_label !!}
				</span>
@endif
@if ( \App\Configuration::isTrue('PRINT_LOT_NUMBER_ON_DOCUMENTS') && $line->lot_references && $line->product->lot_tracking )
@php
	$names = [];
	$lots = explode(',', str_replace(' ', '', $line->lot_references));
	foreach ($lots as $lot) {
		# code...
		$parts = explode(':', $lot);
		$names[] = $parts[0] . ' (' . ($parts[1] ?? '') . ') '; // . ($parts[2] ?? '');
	}
@endphp
				<br />
				<span class="abi-line-rule-label"><i>Lote(s):</i> {!! implode(', ', $names) !!}
				</span>
@endif
			</td>
			<td class="quantity"><span>{{ $line->as_quantity('quantity') }}</span>
			</td>
			<td class="price total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_price('unit_customer_final_price') }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="price total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_priceable( optional($line->product)->recommended_retail_price_tax_inc ) }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="discount total last-column">
				@if ( $line->discount_percent > 0.0 )
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_percent('discount_percent') }}
							<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
				@endif
			</td>
			<td class="discount total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_percent('tax_percent') }}
							<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_price('total_tax_excl') }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
		</tr>
	@endif
@endif

                @endforeach

{{-- VAT Regime :: Intra-Communit --}}
@if ( $document->customer->vat_regime == 1 )

		<tr class="3655">
			<td class="sku first-column">
				<span>{{-- $line->reference --}}</span>
			</td>
			<td class="description" colspan="7">
				<span>
					<span class="item-name"><strong> Entrega intracomunitaria exenta por aplicación del art. 25 de la Ley 37/1992,  del IVA. </strong></span>
					<span class="item-combination-options"></span>
				</span>
			</td>
		</tr>

@elseif ( $document->customer->vat_regime == 2 )

		<tr class="3655">
			<td class="sku first-column">
				<span>{{-- $line->reference --}}</span>
			</td>
			<td class="description" colspan="7">
				<span>
					<span class="item-name"><strong> Operación exenta por exportación en virtud del art. 21 de la Ley 37/1992, del IVA. </strong></span>
					<span class="item-combination-options"></span>
				</span>
			</td>
		</tr>

@endif

	</tbody>

</table>

@endif



@include('templates::customer_invoices.xtranat.totals')



<table class="notes-totals" style="margin-bottom: 2mm !important;">

	<tbody>

		<tr class="no-borders">

			<td class="no-borders">

				<div class="customer-notes">

@if( !optional($document->paymentmethod)->payment_is_cash || 1 )

						<h3>Numero de cuenta: {{ optional(\App\Context::getContext()->company->bankaccount)->iban_presenter(true) }}</h3>

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

@if($document->document_discount_percent>0 && 0)								
								<tr class="xtotal xgrand-total">

									<th class="description"><span>Sub-Total</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">{{ $document->as_priceable($document->total_currency_tax_incl/(1.0-$document->document_discount_percent/100.0)) }}</span></span></td>

								</tr>
								<tr class="xtotal xgrand-total">

									<th class="description"><span>Descuento</span></th>

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">{{ $document->as_percent('document_discount_percent') }}%</span></span></td>

								</tr>
@endif
								<tr class="total grand-total">

									<th class="description" style="background-color: #f5f5f5;"><span>Total a pagar</span></th>
									<!-- p>The euro character:  €  &#0128;  &euro;  &#8364;   :: Not work: &#0128;  </p -->

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">{{ $document->as_price('total_currency_tax_incl') }}<span class="abi-Price-currencySymbol">{{ $document->currency->sign_printable }}</span></span></span></td>

								</tr>
					</tfoot>

				</table>

			</td>

		</tr>

	</tbody>

</table>




@include('templates::customer_invoices.xtranat.payments')




@if ($document->notes_to_customer)

						<h3>OBSERVACIONES:</h3>

<table class="order-details print-friendly" width="100%" style="border: 1px #ccc solid">

	<tbody>

		<tr xclass="no-borders">

			<td xclass="no-borders" style="padding: 2mm;">

						{{ $document->notes_to_customer }}

			</td>

		</tr>

	</tbody>

</table>

@endif



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




<div xclass="tax-summary-wrapper order-details tax-summary" id="footer">
{{--
	< ? php $wpo_wcpdf->footer(); ?>
--}}

{{-- --} }
	{{ $company->name_fiscal }} - {!! \App\Configuration::get('CUSTOMER_INVOICE_CAPTION') !!}
{ {-- --}}


		<table  width="100%" style="height: 1.7cm;" class="print-friendly" xclass="order-details tax-summary" xstyle="border: 1px #ccc solid !important">
			<tbody>
				<tr>
					<td style="padding-right: 2mm">

						{!! \App\Configuration::get('CUSTOMER_INVOICE_CAPTION') !!}

<!-- 
Según el Real Decreto 110/2015 tanto las lámparas led como bajo consumo están sometidas al RAE. Número de registro 6299.
Sus datos se encuentran registrados en una base propiedad de GUSTAVO MEDINA RODRIGUEZ DISTRIBUCIONES S.L.U., inscrita en la Agencia Española de Protección
de datos. Usted en cualquier momento puede ejercer sus derechos de acceso, rectificación, cancelación y/u oponerse a su tratamiento. Estos derechos
pueden ser ejercitados escribiendo a GUSTAVO MEDINA RODRIGUEZ, C/ PRIMAVERA, Nº 20 – 35018 LAS PALMAS DE GRAN CANARIA (LAS PALMAS).
-->
					</td>
					<td width="1.2cm" style="background: #f5f5f5; xborder: 0.2mm #ccc solid !important;"> 
					</td>
				</tr>
			</tbody>
		</table>

	<!-- p><span class="pagenum"></span> / <span class="pagecount">{PAGE_COUNT}</span></p -->


</div><!-- #letter-footer -->

@php

$GLOBALS['var'] = 'Factura nº:   ' . ($document->document_reference ?: 'BORRADOR');

@endphp


<script type="text/php">

    if ( isset($pdf) ) {

        // $pdf->page_text(30, ($pdf->get_height() - 26.89), "Impreso el: " . date('d M Y H:i:s'), null, 10);
        
        if ( 1 || $PAGE_COUNT > 1 || \App\Configuration::isTrue('DEVELOPER_MODE') )
        {
			// $pdf->page_text(($pdf->get_width() - 82), ($pdf->get_height() - 26.89), "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9);

        	$pdf->page_text(($pdf->get_width() - 61), ($pdf->get_height() - 26.89 - 43), "Página", null, 9);

        	$pdf->page_text(($pdf->get_width() - 54), ($pdf->get_height() - 26.89 - 31), "{PAGE_NUM} / {PAGE_COUNT}", null, 9);


// See: https://github.com/dompdf/dompdf/issues/347
$pdf->page_script('
if ( $PAGE_NUM == 1 )
{
               // $pdf->text(($pdf->get_width() - 150), ($pdf->get_height() - 26.89 - 635.0 + 15.3), $PAGE_NUM." de ".$PAGE_COUNT, null, 9);
               $pdf->text(($pdf->get_width() - 150), ($pdf->get_height() - 26.89 - 635.0 - 16.0 - 31.0), $PAGE_NUM." de ".$PAGE_COUNT, null, 9);
}
if ( $PAGE_NUM > 1 )
{
               // https://hotexamples.com/examples/-/Font_Metrics/-/php-font_metrics-class-examples.html
               // $fontBold = \PDF\Font_Metrics::get_font("defaultFont", "bold");
               $fontBold = null;
               $pdf->text(($pdf->get_width() - 180), ($pdf->get_height() - 26.89 - 790.0), $GLOBALS["var"], $fontBold, 9);
}
');

        }


    }

</script>

{{-- 

https://github.com/dompdf

view-source:https://dompdf.net/test/print_header_footer.html

https://groups.google.com/forum/#!forum/dompdf

https://groups.google.com/forum/#!topic/dompdf/X9sl6KLYimM

https://github.com/samuelterra22/laravel-report-generator/blob/master/src/views/general-pdf-template.blade.php

--}}
