

<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name">
                <h1 style="margin-top: 0mm; margin-bottom: 1mm;">Hoja de Producción :: {{ abi_date_form_short($sheet->due_date) }}</h1>
            </div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;"><strong>Resumen de Productos</strong></h3></div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;">Centro de Trabajo: <strong>{{ optional($work_center)->name }}</strong></div>
{{--
            <div class="shop-address" style="margin-top: 2mm;">
                        <strong>Fecha:</strong> {{ abi_date_form_short($sheet->due_date) }} <br>
                
            </div>
--}}
        </td>

        <td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="ximg-rounded" height="45" style="float:right" src="{{ URL::to( \App\Company::imagesPath() . $img ) }}" >

        @endif


        <div class="banner">
        </div>

        </td>

    </tr>

</table>



{{-- Rock n Roll here in. --}}



@if ( $sheet->customerorderlinesGroupedByWorkCenter( $work_center->id )->count() )

<table class="order-details xtax-summary x-print-friendly" style="margin-bottom: 4mm;" xstyle="border: 1px #ccc solid">
      <tbody>

{{--
        <tr>
          <th width="14%">{{l('Product Reference')}}</th>
          <th width="50%">{{l('Product Name')}}</th>
          <th width="12%">{{l('Total')}}</th>
          <th width="12%">{{l('Measure')}}</th>
          <th width="12%">{{l('Per Unit')}}</th>
        </tr>
--}}

    <tr style="background-color: #f5f5f5;">
      <!-- th>{{l('Product ID')}}</th -->
      <!-- th width="10%">{{l('Product Reference')}}</th>
      <th width="40%">{{l('Product Name')}}</th>
      <th width="5%">{{l('Quantity')}}</th>
      <th width="27%"> &nbsp; </th>
      <th width="18%"> &nbsp; </th-->
      <th width="50%"> Producto </th>
      <th width="50%"> Pedidos </th>
    </tr>

  @foreach ($sheet->customerorderlinesGroupedByWorkCenter( $work_center->id ) as $item)
    <tr>
      <td>

{{-- https://stackoverflow.com/questions/47507279/dompdf-table-fixed-column-width-and-break-long-text
     https://stackoverflow.com/questions/5239758/css-truncate-table-cells-but-fit-as-much-as-possible
--}}

      <table width="100%"  style="table-layout:fixed;" xclass="order-details xtax-summary x-print-friendly" xstyle="margin-bottom: 4mm;" xstyle="border: 1px #ccc solid">
        <tbody>
      <!-- td>{{ $item['product_id'] }}</td -->
            <td width="10%"><strong>{{ $item['reference'] }}</strong></td>
            <td width="72%"><div width="100%" style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; width:100%;">{{ $item['name'] }}</div></td>
            <td width="8%" class="text-right" xstyle="padding-right: 16px"><strong>{{ niceQuantity($item['quantity'], 0) }}</strong></td>
            <td width="10%" class="text-left"><strong>&nbsp; {{ $item['measureunit_sign'] }}</strong></td>
          </tbody>
      </table>

      </td>

      <td>

      <table width="100%"  xclass="order-details xtax-summary x-print-friendly" xstyle="margin-bottom: 4mm;" xstyle="border: 1px #ccc solid">
        <tbody>


    {{-- Orders. Gorrino Way! --}}

@php

  $reference = $item['reference'];

  $orders = $sheet->customerorders()
                  ->whereHas('lines', function($q) use ($reference) {
                      $q->where('reference', $reference);
                  })
                  ->with(['lines' => function($q) use ($reference) {
                                        $q->where('reference', $reference);
                                        $q->with('lotitems');
                                    }])
                  ->get();

@endphp


  @foreach ($orders as $order)
          <tr>
            <td width="17%" class="xbutton-pad text-right">

@if( $order->status == 'closed' )
     <u>{{ $order->document_reference ?: 'Borrador' }}</u>
@else
    {{ $order->document_reference ?: 'Borrador' }}
@endif

                @if (0 && $order->reference)
                    <br />[{{ $order->reference }}
                @endif

            </td>
            <td width="73%" xstyle="border-bottom: 1px #ccc solid;">{!! $order->customerInfo() !!}

@if( $order->status != 'closed' )

                @foreach ($order->lines->where( 'reference', $reference) as $line)

                    @if( !$line->lotitems->count() )
                        @continue
                    @endif

@if ($line->lotitems->count() > 1)
        <table>
            <tr>
                <td style="border-bottom: 0px #ccc solid !important;"><i>Lotes:</i></td>
                <td style="border-bottom: 0px #ccc solid !important;"><i>
                    @foreach( $line->lotitems as $lotitem )
                        {{ $lotitem->as_quantity('quantity') }} ud. Lote <b>{{ $lotitem->lot->reference }}</b> ({{ abi_date_short( $lotitem->lot->expiry_at ) }})<br />
                    @endforeach                 
                </i></td>
            </tr>
        </table>
@else
                <br />
                <span class="">
                <i>Lote: 
                    @foreach( $line->lotitems as $lotitem )
                        <b>{{ $lotitem->lot->reference }}</b> ({{ abi_date_short( $lotitem->lot->expiry_at ) }})<br />
                        @break
                    @endforeach
                </i>
                </span>

@endif

{{--
                    <br />
                    @foreach($line->lotitems as $item)
                        <i><b>{{ $item->lot->reference }}</b></i> ({{ niceQuantity($item->quantity) }}) {{ abi_date_short( $lotitem->lot->expiry_at ) }}   <br />
                    @endforeach
--}}
                @endforeach

@endif

            </td>
            <td width="10%" class="text-right" xstyle="border-right: 1px #ccc solid;"><strong>{{ niceQuantity($order->lines->where( 'reference', $reference)->sum('quantity')) }}</strong>
            </td>
{{--            
            <td xstyle="border-bottom: 1px #ccc solid;">
                @if ( $order->hasShippingAddress() )

                    Entrega: {{ $order->shippingaddress->alias }} <br />

                @endif

                @if ($order->all_notes)
                    Notas: {!! nl2br($order->all_notes) !!} <br />
                @endif
            </td>
            <td>{{ $order->shippingmethod->name ?? '' }} 

              { {-- {!! optional($order->carrier)->name ? '<br />' . $order->carrier->name : '' !!} --} }</td>
--}}
          </tr>
  @endforeach {{-- Orders --}}

          </tbody>
      </table>


      </td>
    </tr>
  @endforeach {{-- Products --}}

    </tbody>
</table>

<p><b>NOTA:</b> Los pedidos subrayados están cerrados y no tienen lotes asignados.</p>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif







<script type="text/php">

    if ( isset($pdf) ) {

        $pdf->page_text(30, ($pdf->get_height() - 26.89), "Impreso el: " . date('d M Y H:i:s'), null, 9);
        
        if ( 1 || $PAGE_COUNT > 1 )
        {
            $pdf->page_text(($pdf->get_width() - 82), ($pdf->get_height() - 26.89), "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9);

if ( $PAGE_NUM == 1 )
{
               // $pdf->page_text(($pdf->get_width() - 150), ($pdf->get_height() - 26.89 - 635.0), "{PAGE_NUM} de {PAGE_COUNT}", null, 9);
}
        }


    }

</script>
