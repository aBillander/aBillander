


@if ($sheet->productionorders->whereIn('product_reference', $family['assemblies'])->count())

@php

/*
// https://stackoverflow.com/questions/26176245/laravel-mysql-how-to-order-results-in-the-same-order-as-in-wherein-clause
// https://stackoverflow.com/questions/41688648/how-to-make-laravel-wherein-not-sorted-automatically

$temp = $family['assemblies'];
$tempStr = implode(',', $temp);
$robjeks = DB::table('objek')
    ->whereIn('id', $temp)
    ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
    ->get();
*/

// 
$order_by = $family['assemblies'];

@endphp

  @foreach ($sheet->productionorders->whereIn('product_reference', $family['assemblies'])->sortBy(
      function($model) use ($order_by){
          return array_search($model->product_reference, $order_by);
      }) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


<div class="tax-summary-wrapper print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 4mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="14%">{{l('Product Reference')}}</th>
          <th width="50%">{{l('Product Name')}}</th>
          <th width="12%">{{l('Total')}}</th>
          <th width="12%">{{l('Unit')}}</th>
          <th width="12%">{{l('Quantity')}}</th>
        </tr>


    <tr style="font-weight: bold;">
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td>{{ niceQuantity($order->planned_quantity, $product->measureunit->decimalPlaces) }}</td>
      <td>{{ $product->measureunit->name }}</td>
      <td> </td>
    </tr>

{{-- Ingredients here: --}}

@foreach ($order->productionorderlines as $line)

    <tr>
      <td style="padding-left: 16px">{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td class="text-right" style="padding-right: 16px">{{ niceQuantity($line->required_quantity, $line->product->measureunit->decimalPlaces) }}</td>
      <td>{{ $line->product->measureunit->name }}</td>
      <td style="padding-right: 16px">{{ niceQuantity($line->required_quantity / $order->planned_quantity, $line->product->measureunit->decimalPlaces) }}</td>
    </tr>

@endforeach


    </tbody>
</table>
</div><!-- div class="panel-body" -->


{{-- Ingredients here: --}}

{{-- 10601 after 1000, 1010, 10610, 10611 --}}

@php

$lines_10601 = $order->productionorderlines->where('reference', '10601');

foreach ($lines_10601 as $line)
{
  if($order->product_reference == '10611')
  if ( array_key_exists( $key_1001_1011, $qty_10601) )
      $qty_10601[$key_1001_1011] += $line->required_quantity;
  else
      $qty_10601[$key_1001_1011]  = $line->required_quantity;
  
  if($order->product_reference == '10610')
  if ( array_key_exists( $key_1002_1012, $qty_10601) )
      $qty_10601[$key_1002_1012] += $line->required_quantity;
  else
      $qty_10601[$key_1002_1012]  = $line->required_quantity;

}


@endphp



  @endforeach

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif


{{-- abi_r($qty_10601) --}}
