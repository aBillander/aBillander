

		<tr class="3655">
			<td class="sku first-column">
				<span>{{ $line->reference }}</span>
			</td>
			<td class="description">
				<span>
					<span class="item-name">{{ $line->name }}</span><br />
					<span class="item-combination-options">RAE - {{ $line->product->ecotax->name }}</span>
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
			</td>
			<td class="quantity"><span>{{ $line->as_quantity('quantity') }}</span>
			</td>
			<td class="barcode">
				<span>
					<span class="item-name">{{ $line->product->ean13 }}</span>
				</span>
			</td>
			<td class="price total last-column">
@if ($line->unit_supplier_final_price == 0.0)
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_priceable( 0.0 )}}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span><br />
					<span class="abi-Price-amount amount">{{ $line->as_priceable( 0.0 ) }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
@else
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_priceable( $line->as_price('unit_supplier_final_price') - $line->product->ecotax->amount )}}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span><br />
					<span class="abi-Price-amount amount">{{ $line->as_priceable( $line->product->ecotax->amount ) }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
@endif
			</td>
			<td class="discount total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_percent('discount_percent') }}
							<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="discount total last-column">
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_percent('tax_percent') }}
							<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
			</td>
			<td class="total last-column">
@if ($line->unit_supplier_final_price == 0.0)
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_price('total_tax_excl') }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span><br />
					<span class="abi-Price-amount amount">{{ $line->as_priceable( 0.0 ) }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
@else
				<span>
					<span class="abi-Price-amount amount">{{ $line->as_price('total_tax_excl') - $line->as_priceable( $line->product->ecotax->amount * $line->quantity ) }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span><br />
					<span class="abi-Price-amount amount">{{ $line->as_priceable( $line->product->ecotax->amount * $line->quantity ) }}
						<!-- span class="abi-Price-currencySymbol">€</span -->
					</span>
				</span>
@endif
			</td>
		</tr>
