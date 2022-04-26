

<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name">
                <h1 style="margin-top: 0mm; margin-bottom: 1mm;">Hoja de Producción :: {{ abi_date_form_short($sheet->due_date) }}</h1>
            </div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;"><strong>Resumen de Productos</strong></h3></div>
{{--
            <div class="shop-address" style="margin-top: 2mm;">
                        <strong>Fecha:</strong> {{ abi_date_form_short($sheet->due_date) }} <br>
                
            </div>
--}}
        </td>

        <td class="header">


        @if ($img = AbiContext::getContext()->company->company_logo)

            <img class="ximg-rounded" height="45" style="float:right" src="{{ URL::to( AbiCompany::imagesPath() . $img ) }}" >

        @endif


        <div class="banner">
        </div>

        </td>

    </tr>

</table>



{{-- Rock n Roll here in. --}}



@if ( $sheet->customerorderlinesGrouped()->count() )

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
      <th width="10%">{{l('Product Reference')}}</th>
      <th width="40%">{{l('Product Name')}}</th>
      <th width="5%">{{l('Quantity')}}</th>
      <th width="27%"> &nbsp; </th>
      <th width="18%"> &nbsp; </th>
    </tr>

  @foreach ($sheet->customerorderlinesGrouped() as $item)
    <tr>
      <!-- td>{{ $item['product_id'] }}</td -->
      <td width="10%"><strong>{{ $item['reference'] }}</strong></td>
      <td width="40%"><strong>{{ $item['name'] }}</strong></td>
      <td width="5%" class="text-right" style="padding-right: 16px"><strong>{{ niceQuantity($item['quantity'], 0) }}</strong></td>
      <td width="27%"><strong>{{ $item['measureunit'] }}</strong></td>
      <td width="18%"> &nbsp; </td>

    </tr>

    {{-- Orders. Gorrino Way! --}}

@php

  $reference = $item['reference'];

  $orders = $sheet->customerorders()->whereHas('lines', function($q) use ($reference) {
      $q->where('reference', $reference);
  })->get();

@endphp


  @foreach ($orders as $order)
    <tr>
      <td class="xbutton-pad text-right">{{ $order->document_reference ?: $order->id }}

          @if ($order->reference)
              <br />[{{ $order->reference }}
          @endif

      </td>
      <td xstyle="border-bottom: 1px #ccc solid;">{!! $order->customerInfo() !!}
      </td>
      <td xstyle="border-bottom: 1px #ccc solid;">{{ niceQuantity($order->lines->where( 'reference', $reference)->sum('quantity')) }}
      </td>
      <td xstyle="border-bottom: 1px #ccc solid;">
          @if ( $order->hasShippingAddress() )

              Entrega: {{ $order->shippingaddress->alias }} <br />

          @endif

          @if ($order->all_notes)
              Notas: {!! nl2br($order->all_notes) !!} <br />
          @endif
      </td>
      <td>{{ $order->shippingmethod->name ?? '' }} 

        {{-- {!! optional($order->carrier)->name ? '<br />' . $order->carrier->name : '' !!} --}}</td>

    </tr>
  @endforeach


  @endforeach

    </tbody>
</table>


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
