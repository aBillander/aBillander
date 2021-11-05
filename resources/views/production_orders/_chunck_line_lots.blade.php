

@if ($line->lotitems->count() == 0)
				<br />
				<span class="alert-danger">
				<i><strong>Lote: &nbsp;</strong>
				</i>
				</span>
@else

@if ($line->lotitems->count() > 1)
		<table>
			<tr>
				<!-- td style="border-bottom: 0px #ccc solid !important;"><i>Lotes:</i></td -->
				<td style="border-bottom: 0px #ccc solid !important;"><i>
					@foreach( $line->lotitems as $lotitem )
						&nbsp;Lote: <a xclass="btn btn-sm btn-warning " href="{{ URL::to('lots/' . $lotitem->lot->id . '/edit') }}"  title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $lotitem->lot->reference }}</a> &nbsp; <i class="fa fa-calendar text-info" title="{{ abi_date_short( $lotitem->lot->expiry_at ) }}"></i> ({{ $lotitem->lot->measureunit->quantityable($lotitem->quantity) }})<br />
					@endforeach					
				</i></td>
			</tr>
		</table>
@else
				<br />
				<span class="">
				<i>Lote: 
					@foreach( $line->lotitems as $lotitem )
						<a xclass="btn btn-sm btn-warning " href="{{ URL::to('lots/' . $lotitem->lot->id . '/edit') }}"  title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $lotitem->lot->reference }}</a> &nbsp; <i class="fa fa-calendar text-info" title="{{ abi_date_short( $lotitem->lot->expiry_at ) }}"></i><br />
						@break
					@endforeach
				</i>
				</span>

@endif

@endif