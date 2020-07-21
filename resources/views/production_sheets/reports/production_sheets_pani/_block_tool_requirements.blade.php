
@php

// Long life to Gorrino Style!!!

$pani_tool = ['90000', '90001', '90100', ];
$pani_tool_id = [1, 2, 3, ];

// Force-brute strategy:

$pani_tool_qty = [];
$pani_tool_qty['90000'] = $pani_tool_qty['90001'] = $pani_tool_qty['90100'] = 0.0;

foreach ($sheet->productionorders->where('procurement_type', 'manufacture')->where('product_reference', '<', '2000') as $order)
{
    // $product = $order->product;
    $product = \App\Product::find( $order->product_id );
    $pani_tool_qty['90000'] += $product->getChildToolQuantity( $pani_tool_id[0], $order->planned_quantity );
    $pani_tool_qty['90001'] += $product->getChildToolQuantity( $pani_tool_id[1], $order->planned_quantity );
    $pani_tool_qty['90100'] += $product->getChildToolQuantity( $pani_tool_id[2], $order->planned_quantity );
}

@endphp

@if ($sheet->productionordertoollinesGrouped()->whereIn('reference', $pani_tool)->count())


<div class="xtax-summary-wrapper xprint-friendly text-left">
<table class="order-details tax-summary xprint-friendly" xstyle="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <thead>
        <tr>
          <th>{{l('Tool Reference')}}</th>
          <th>{{l('Tool Name')}}</th>
          <th>{{l('Quantity')}}</th>
          <th>{{l('Location')}}</th>
        </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionordertoollinesGrouped()->whereIn('reference', $pani_tool)->sortBy('product_reference') as $order)

    <tr>
      <td>{{ $order['reference'] }}</td>
      <td>{{ $order['name'] }}</td>
      <td>{{-- {{ number_format( $order['quantity'], 0, '', '' ) }}<br /> --}}{{ number_format($pani_tool_qty[$order['reference']], 0, '', '') }}</td>
      <td>{{ $order['location'] }}</td>
    </tr>
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

