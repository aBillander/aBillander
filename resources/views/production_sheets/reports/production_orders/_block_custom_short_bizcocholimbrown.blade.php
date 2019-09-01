

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$qty_30100 = [];
$keys_30100 = ['30101', '30102', '30103', '30300'];

$reference_30100 = $name_30100 = '';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $keys_30100)->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $keys_30100) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}

{{-- 30100 after '30101', '30102', '30103', '30300' --}}

@php

$lines_30100 = $order->productionorderlines->where('reference', '30100');

foreach ($lines_30100 as $line)
{
  if ( array_key_exists( $order->product_reference, $qty_30100) )
      $qty_30100[$order->product_reference] += $line->required_quantity;
  else {
      $qty_30100[$order->product_reference]  = $line->required_quantity;
      $reference_30100 = $line->reference;
      $name_30100 = $line->name;
      $unit_30100 = $line->product->measureunit->sign;
  }
}

@endphp

  @endforeach


{{-- Special 30102 --}}

@php

$order_30102 = $sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', ['30102'])->first();

$lines_30101 = $order_30102->productionorderlines->where('reference', '30101');

$qty_30101 = 0;

foreach ($lines_30101 as $line)
{
      $qty_30101 += $line->required_quantity;
}



$order_30101 = $sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', ['30101'])->first();

$lines_30100 = $order_30101->productionorderlines->where('reference', '30100');

$qty_30101_30100 = 0;

foreach ($lines_30100 as $line)
{
      $qty_30101_30100 += $line->required_quantity;
}

$ratio = $qty_30101_30100 / $order_30101->planned_quantity;

$qty_30100['30102'] = $qty_30101 * $ratio;

// Final touches
ksort($qty_30100);

@endphp


<div class="print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="30%">
@if( count($qty_30100) )
            [{{ $reference_30100 }}] {{ $name_30100 }}
@endif
          </th>
          <th width="5%"> </th>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">

          </th>
        </tr>

        <tr xstyle="font-weight: bold;">
          <td>
@if( count($qty_30100) )
      @foreach ($qty_30100 as $key => $qty)
            {{ $key }}: {{ niceQuantity($qty) }} {{ $unit_30100 }}<br />
      @endforeach
@endif
          </td>
          <td> </td>
          <td>

          </td>
          <td> </td>
          <td>

          </td>
        </tr>

    </tbody>
</table>
</div><!-- div class="panel-body" -->

@else

@endif


        <div style="margin-bottom: 0px">&nbsp;</div>


{{--

{{ abi_r($qty_10709) }}

{{ abi_r($qty_20001) }}

{{ abi_r($qty_20601) }}

--}}