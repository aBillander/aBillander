


<table class="head container">

	<tr>

		<td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="img-rounded" height="{{ '60' }}" src="{{ URL::to( \App\Company::imagesPath() . $img ) }}">

        @endif


		<div class="banner">

			&nbsp; {{-- !! \App\Configuration::get('CUSTOMER_INVOICE_BANNER') !! --}}

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
			<div class="shop-name"><h3>{{ $name }}</h3></div>

			<div class="shop-address">
                        {{ $company->address->address1 }} {{ $company->address->address2 }}<br />
                        {{ $company->address->postcode }} {{ $company->address->city }} ({{ $company->address->state->name }})<br />
                        CIF: {{ $company->identification }} / Tel: {{ $company->address->phone }}<br />
                        {{ $company->address->email }}<br />
				
			</div>

		</td>

	</tr>

</table>



<h1 class="document-type-label"> ALBARAN Transferencia entre Almacenes </h1>



<table class="order-data-addresses" xstyle="border: 1px #ccc  !important; background-color: red" >

	<tr>

		<td class="address shipping-address">

			<h3>Origen:</h3>

            <div class="shop-name"><h3>

			@if ( $document->warehouse->address->name_commercial )

				{{ $document->warehouse->address->name_commercial }}

			@else

            	{{ $document->warehouse->address->contact_name }}

			@endif

			</h3></div>

            {{ $document->warehouse->address->address1 }} {{ $document->warehouse->address->address2 }}<br />

            {{ $document->warehouse->address->postcode}} {{ $document->warehouse->address->city }} <br />

            {{ $document->warehouse->address->state->name }}


			@if ( $document->warehouse->address->mail )

				<div class="billing-email">{{ $document->warehouse->address->mail }}</div>

			@endif
            
            <!-- div class="cif">CIF/NIF: { { $document->customer->identification }} <span style="float: right; xmargin-left: 10mm">[{ { $document->customer->id }}]</span></div -->

			<div class="billing-phone">

			@if ( $document->warehouse->address->phone )

				Tel. {{ $document->warehouse->address->phone }}

			@else

				Tel. {{ $company->address->phone }}

			@endif

			</div>
			
		</td>

		<td class="address billing-address">

			<h3>Destino:</h3>

            <div class="shop-name"><h3>

			@if ( $document->warehousecounterpart->address->name_commercial )

				{{ $document->warehousecounterpart->address->name_commercial }}

			@else

            	{{ $document->warehousecounterpart->address->contact_name }}

			@endif

			</h3></div>

            {{ $document->warehousecounterpart->address->address1 }} {{ $document->warehousecounterpart->address->address2 }}<br />

            {{ $document->warehousecounterpart->address->postcode}} {{ $document->warehousecounterpart->address->city }} <br />

            {{ $document->warehousecounterpart->address->state->name }}


			@if ( $document->warehousecounterpart->address->mail )

				<div class="billing-email">{{ $document->warehousecounterpart->address->mail }}</div>

			@endif
            
            <!-- div class="cif">CIF/NIF: { { $document->customer->identification }} <span style="float: right; xmargin-left: 10mm">[{ { $document->customer->id }}]</span></div -->

			<div class="billing-phone">

			@if ( $document->warehousecounterpart->address->phone )

				Tel. {{ $document->warehousecounterpart->address->phone }}

			@else

				Tel. {{ $company->address->phone }}

			@endif

			</div>
			
		</td>

		<td class="order-data">

			<table width="100%" xstyle="border: 1px #ccc  !important; background-color: white">

				<tr class="order-number">

					<th>Albarán nº:</th>

					<td style="font-size: 11pt;"><strong>{{ $document->document_reference ?? 'BORRADOR'.' ['.$document->id.']' }}</strong></td>

				</tr>

				<tr class="order-page">

					<th>Página:</th>

					<td> &nbsp; </td>

				</tr>

				<tr class="order-date">

					<th>Fecha del Albarán:</th>

					<td>{{ abi_date_short($document->document_date) }}</td>

				</tr>

				<tr class="order-number">

					<th> </th>

					<td> </td>

				</tr>

				<tr class="order-date">

					<th>Bultos:</th>

					<td>{{ $document->number_of_packages }}</td>

				</tr>

				<tr class="order-date">

					<th>Peso / Volumen:</th>

					<td>@if ($document->weight)
							{{ $document->as_quantityable( $document->weight, 2 ) }}
						@else
						 - 
						@endif kg. / 
						@if ($document->volume)
							{{ $document->as_quantityable( $document->volume, 3 ) }}
						@else
						 - 
						@endif m<sup>3</sup></td>

				</tr>

				<!-- tr class="order-number">

					<th>Albarán nº:</th>

					<td>{{ $document->document_reference }}</td>

				</tr -->

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
			<th class="description" width="35%" style="border: 1px #ccc solid">
				<span>Producto</span>
			</th>
			<th class="quantity last-column" width="8%" style="border: 1px #ccc solid">
				<span>Cantidad</span>
			</th>
			<th class="barcode" width="14%" style="border: 1px #ccc solid">
				<span>Cód. Barras</span>
			</th>
			<th class="price" width="8%" style="border: 1px #ccc solid">
				<span>Unidad</span>
			</th>
			<th class="discount" width="25%" style="border: 1px #ccc solid">
				<span>Notas</span>
			</th>
		</tr>

	</thead>

	<tbody>

                @foreach ($document->documentlines->sortBy('line_sort_order') as $line)

			    @if ( 
			    			0 &&
			    			( $line->line_type != 'product' ) &&
			    			( $line->line_type != 'service' ) &&
			    			( $line->line_type != 'comment' )
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
			<td class="quantity"><span>{{ $line->as_quantity('quantity') }}</span>
			</td>
			<td class="barcode">
				<span>
					<span class="item-name">{{ optional($line->product)->ean13 }}</span>
				</span>
			</td>
			<td class="price total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->measureunit->name }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->notes }}
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

@if ($document->notes_from_customer && 0)

						<h3>Notas:</h3>

						{{ $document->notes_from_customer }}
@endif
				</div>	

			</td>

			<td class="no-borders totals-cell" style="width:40%">

				<table class="totals" style="display: hidden">

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

									<th class="description" style="background-color: #f5f5f5;"><span>Total a pagar</span></th>
									<!-- p>The euro character:  €  &#0128;  &euro;  &#8364;   :: Not work: &#0128;  </p -->

									<td class="price"><span class="totals-price"><span class="abi-Price-amount amount">{ { $document->as_price('total_currency_tax_incl') }}<span class="abi-Price-currencySymbol">{ { $document->currency->sign_printable }}</span></span></span></td>

								</tr>
					</tfoot>

				</table>

			</td>

		</tr>

	</tbody>

</table>




@if ($document->notes_to_counterpart)

						<h3>OBSERVACIONES:</h3>

<table class="order-details print-friendly" width="100%" style="border: 1px #ccc solid">

	<tbody>

		<tr xclass="no-borders">

			<td xclass="no-borders" style="padding: 2mm;">

						{{ $document->notes_to_counterpart }}

			</td>

		</tr>

	</tbody>

</table>

@endif





<!-- div>Firma del cliente<br>
<br>
<br>
________________________________________
</div -->



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
               $pdf->text(($pdf->get_width() - 150), ($pdf->get_height() - 26.89 - 635.0), $PAGE_NUM." de ".$PAGE_COUNT, null, 9);
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
