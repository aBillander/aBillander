
@php

// Long life to Gorrino Style!!!

$pani_sourdough = ['10500', '10501', '10502', ];
$pani_sourdough_id = [135, 136, 137, ];

// Force-brute strategy:

$pani_sourdough_qty = [];
$pani_sourdough_qty['10500'] = $pani_sourdough_qty['10501'] = $pani_sourdough_qty['10502'] = 0.0;

foreach ($sheet->productionorders->where('procurement_type', 'manufacture')->where('product_reference', '<', '2000') as $order)
{
    // $product = $order->product;
    $product = \App\Product::find( $order->product_id );
    $pani_sourdough_qty['10500'] += $product->getChildProductQuantity( $pani_sourdough_id[0], $order->planned_quantity );
    $pani_sourdough_qty['10501'] += $product->getChildProductQuantity( $pani_sourdough_id[1], $order->planned_quantity );
    $pani_sourdough_qty['10502'] += $product->getChildProductQuantity( $pani_sourdough_id[2], $order->planned_quantity );
}

@endphp

@if ($sheet->productionorders->where('schedule_sort_order', $schedule_sort_order)->whereIn('product_reference', $pani_sourdough)->count())


<div class="xtax-summary-wrapper xprint-friendly text-left">
<table class="order-details tax-summary xprint-friendly" xstyle="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th>{{l('Product Reference')}}</th>
          <th>{{l('Product Name')}}</th>
          <th>{{l('Quantity')}}</th>
          <th>{{l('Unit')}}</th>
        </tr>
  
  @foreach ($sheet->productionorders->where('schedule_sort_order', $schedule_sort_order)->whereIn('product_reference', $pani_sourdough)->sortBy('product_reference') as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp

    <tr xstyle="font-weight: bold;">
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td>{{-- {{ $product->as_quantityable($order->planned_quantity) }}<br> --}}{{ $order->as_quantityable($pani_sourdough_qty[$order->product_reference], 2) }}</td>
      <td>{{ $product->measureunit->name }}</td>
    </tr>

{{-- Ingredients here: --}}
{{--
@foreach ($order->productionorderlines as $line)

    <tr>
      <td style="padding-left: 16px">{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td style="padding-left: 16px">{{ $product->as_quantityable($line->required_quantity) }}</td>
      <td>{{ $line->product->measureunit->name }}</td>
    </tr>

@endforeach

    <tr>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
    </tr>
--}}


  @endforeach
    </tbody>
</table>
</div>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

