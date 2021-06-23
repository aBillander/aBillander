
@if( $block_lines && $block_lines->count() )

            <div class="panel {{ $panel_class }}">

              <div class="panel-heading">
                <h3 class="panel-title">{{ $block_title }}
               </h3>
              </div>
              <div class="panel-body">

<div id="div_customerorders">
   <div class="table-responsive">

<table id="customerorders" class="table table-hover">
    <!-- thead>
        <tr>
            <th>{{l('Stock on hand')}}</th>
            <th>{{l('Total Allocated Stock')}}</th>
            <th>{{l('Production Sheet Allocated')}}</th>
            <th>{{l('Available Stock')}}</th>
            <th> </th>
        </tr>
    </thead -->
    <tbody>

@foreach( $block_lines as $line )
        <tr>
            <td>
                <a xclass="btn btn-sm btn-warning" href="{{ URL::to($line->getDocumentRoute().'/' . $line->document->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}" target="_blank">
                	{{ $line->document->document_reference ?: l('Draft', 'layouts') }}
                </a>
            </td>
            <td>{!! $line->document->customerInfo() !!}

@if ( $line->lotitems->count() > 0 )

@if ($line->lotitems->count() > 1)
		<table>
			<tr>
				<td style="vertical-align: top; border-bottom: 0px #ccc solid !important;"><i>Lotes:</i>&nbsp;</td>
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



            </td>
            <td>{{ $line->as_quantity('quantity') }}</td>
            <td class="button-pad">

@if( \App\Configuration::isTrue('ENABLE_LOTS') && $product->lot_tracking )

                <a class="btn btn-sm btn-grey show-stock-availability" data-id="{ {$document->id} }" title="{{l('Allocate Lots')}}" onclick="alert('You naughty, naughty!'); return false;"><i class="fa fa-window-restore"></i></a>

                <a class="btn btn-sm alert-danger" href="{ { URL::to($model_path.'/' . $document->id . '/edit') } }" title="{{l('Un-Allocate Lots')}}" target="_blank" onclick="alert('You naughty, naughty!'); return false;"><i class="fa fa-window-close"></i></a>
@endif

            </td>
        </tr>
@endforeach

    </tbody>
</table>

   </div>
</div>
                  

              </div><!-- div class="panel-body" ENDS -->

            </div>

@endif
