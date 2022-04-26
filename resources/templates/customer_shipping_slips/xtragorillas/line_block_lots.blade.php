
@if ( AbiConfiguration::isTrue('PRINT_LOT_NUMBER_ON_DOCUMENTS') )

@if ($line->lotitems->count() > 1)
		<table>
			<tr>
				<td style="border-bottom: 0px #ccc solid !important;"><i>Lotes:</i></td>
				<td style="border-bottom: 0px #ccc solid !important;"><i>
					@foreach( $line->lotitems as $lotitem )
						{{ $lotitem->as_quantity('quantity') }} ud. Lote {{ $lotitem->lot->reference }} ({{ abi_date_short( $lotitem->lot->expiry_at ) }})<br />
					@endforeach					
				</i></td>
			</tr>
		</table>
@else
				<br />
				<span class="">
				<i>Lote: 
					@foreach( $line->lotitems as $lotitem )
						{{ $lotitem->lot->reference }} ({{ abi_date_short( $lotitem->lot->expiry_at ) }})<br />
						@break
					@endforeach
				</i>
				</span>

@endif

@endif
