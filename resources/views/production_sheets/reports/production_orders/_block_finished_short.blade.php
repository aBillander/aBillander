

@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


<div class="tax-summary-wrapper print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="14%">{{l('Product Reference')}}</th>
          <th width="62%">{{l('Product Name')}}</th>
          <th width="12%">{{l('Quantity')}}</th>
          <th width="12%">{{l('Unit')}}</th>
        </tr>
    </tbody>
</table>
</div><!-- div class="panel-body" -->


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp

<div class="tax-summary-wrapper print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <!-- tr>
          <th width="14%">{{l('Product Reference')}}</th>
          <th width="50%">{{l('Product Name')}}</th>
          <th width="12%">{{l('Quantity')}}</th>
          <th width="12%">{{l('Unit')}}</th>
          <th width="12%">{{l('Total')}}</th>
        </tr -->


    <tr xstyle="font-weight: bold;">
      <td width="14%">{{ $order->product_reference }}</td>
      <td width="62%">{{ $order->product_name }}</td>
      <td width="12%" class="text-right" style="padding-right: 24px">{{ niceQuantity($order->planned_quantity) }}</td>
      <td width="12%">{{ $product->measureunit->name }}</td>
    </tr>

{{-- Ingredients here: --}}

{{-- 10601 after 1000, 1010, 10610, 10611 --}}

@php

$lines_10601 = $order->productionorderlines->where('reference', '10601');

foreach ($lines_10601 as $line)
{
  if ( array_key_exists( $key_1000_1010, $qty_10601) )
      $qty_10601[$key_1000_1010] += $line->required_quantity;
  else {
      $qty_10601[$key_1000_1010]  = $line->required_quantity;
      $reference_10601 = $line->reference;
      $name_10601 = $line->name;
  }
}


$lines_20001 = $order->productionorderlines->where('reference', '20001');

foreach ($lines_20001 as $line)
{
  if ( array_key_exists( $key_1001_1011, $qty_20001) )
      $qty_20001[$key_1001_1011] += $line->required_quantity;
  else {
      $qty_20001[$key_1001_1011]  = $line->required_quantity;
      $reference_20001 = $line->reference;
      $name_20001 = $line->name;
  }
}


$lines_20601 = $order->productionorderlines->where('reference', '20601');

foreach ($lines_20601 as $line)
{
  if ( array_key_exists( $key_1002_1012, $qty_20601) )
      $qty_20601[$key_1002_1012] += $line->required_quantity;
  else {
      $qty_20601[$key_1002_1012]  = $line->required_quantity;
      $reference_20601 = $line->reference;
      $name_20601 = $line->name;
  }
}

@endphp



{{-- -- } }
@foreach ($order->productionorderlines as $line)

@if ( $line->product->is_packaging )
@continue
@endif

    <tr>
      <td style="padding-left: 16px">{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td class="text-right" style="padding-left: 16px">{{ niceQuantity($line->required_quantity) }}</td>
      <td>{{ $line->product->measureunit->name }}</td>
    </tr>

@endforeach
{ { -- --}}

    </tbody>
</table>

</div><!-- div class="panel-body" -->

  @endforeach

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

{{--
{{ abi_r($qty_10601) }}

{{ abi_r($qty_20001) }}

{{ abi_r($qty_20601) }}
--}}
