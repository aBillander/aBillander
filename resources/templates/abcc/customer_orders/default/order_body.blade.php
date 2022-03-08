


<table class="head container">

	<tr>

		<td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="img-rounded" height="{{ '60' }}" src="{{ URL::to( \App\Company::imagesPath() . $img ) }}">

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



<h1 class="document-type-label"> Pedido </h1>



<table class="order-data-addresses">

	<tr>

		<td class="address shipping-address">

			<h3>Dirección de Entrega:</h3>

            {{ $document->shippingaddress->name_commercial }}<br />

			@if ( $document->shippingaddress->contact_name != $document->shippingaddress->name_commercial )

            	{{ $document->shippingaddress->contact_name }}<br />

			@endif

            {{ $document->shippingaddress->address1 }} {{ $document->shippingaddress->address2 }}<br />

            {{ $document->shippingaddress->postcode}} {{ $document->shippingaddress->city }} 

            {{ $document->shippingaddress->state->name }}


			@if ( $document->shippingaddress->email )

				<div class="billing-email">{{ $document->shippingaddress->email }}</div>

			@endif

			@if ( $document->customer->identification )
            
            <div class="cif">CIF/NIF: {{ $document->customer->identification }}</div>

			@endif

			@if ( $document->shippingaddress->phone )

				<div class="billing-phone">Tel. {{ $document->shippingaddress->phone }}</div>

			@endif
			
		</td>

		<td xclass="address billing-address">
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

					<th>Pedido nº:</th>

					<td>{{ $document->document_reference }}</td>

				</tr>

				<tr class="order-date">

					<th>Fecha del Pedido:</th>

					<td>{{ abi_date_short($document->document_date) }}</td>

				</tr>

				@if ( $document->shippingmethod )

				<tr class="shipping-method">

					<th>Método de Envío:</th>

					<td>{{ $document->shippingmethod->name }}</td>

				</tr>

				@endif

				<!-- tr class="order-number">

					<th>Albarán nº:</th>

					<td>{{ $document->document_reference }}</td>

				</tr -->

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
			<th class="quantity last-column">
				<span>Cantidad</span>
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
			<td class="quantity last-column"><span>{{ $line->as_quantity('quantity') }}</span>
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

@if ($document->notes_from_customer)

						<h3>Notas:</h3>

						{{ $document->notes_from_customer }}
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
					</tfoot>

				</table>

			</td>

		</tr>

	</tbody>

</table>



<!-- div>Firma del cliente<br>
<br>
<br>
________________________________________
</div -->



<div id="footer">
{{--
	< ? php $wpo_wcpdf->footer(); ?>
--}}

{{ $company->name_fiscal }} - Este documento es informativo, y no se deriva de él ninguna obligación contractual.

	<!-- p><span class="pagenum"></span> / <span class="pagecount">{PAGE_COUNT}</span></p -->


<script type="text/php">

    if ( isset($pdf) ) {

        $pdf->page_text(30, ($pdf->get_height() - 26.89), "Impreso el: " . date('d M Y H:i:s'), null, 10);
        
        if ( $PAGE_COUNT > 0 )
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
