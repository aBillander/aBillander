
@php

// Long life to Gorrino Style!!!

$pani_tray = ['10706', '10707', '10708', ];
$pani_tray_id = [1, 2, 3, ];

@endphp

@if ($sheet->productionorders->whereIn('product_reference', $pani_tray)->count())

@php

/*
// https://stackoverflow.com/questions/26176245/laravel-mysql-how-to-order-results-in-the-same-order-as-in-wherein-clause
// https://stackoverflow.com/questions/41688648/how-to-make-laravel-wherein-not-sorted-automatically

$temp = $pani_tray;
$tempStr = implode(',', $temp);
$robjeks = DB::table('objek')
    ->whereIn('id', $temp)
    ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
    ->get();
*/

// 
$order_by = $pani_tray;

@endphp


<div class="tax-summary-wrapper print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 4mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="14%">{{l('Product Reference')}}</th>
          <th width="50%">{{l('Product Name')}}</th>
          <th width="12%">{{l('Total')}}</th>
          <th width="12%">{{l('Measure')}}</th>
          <th width="12%">{{l('Per Unit')}}</th>
        </tr>

  @foreach ($sheet->productionorders->whereIn('product_reference', $pani_tray)->sortBy(
      function($model) use ($order_by){
          return array_search($model->product_reference, $order_by);
      }) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


    <tr xstyle="font-weight: bold;">
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td>{{ niceQuantity($order->planned_quantity, $product->measureunit->decimalPlaces) }}</td>
      <td>{{ $product->measureunit->name }}</td>
      <td> </td>
    </tr>


{{-- Units per Tray --}}
@if ($product->units_per_tray)

    <tr xstyle="font-weight: bold;">
      <td> </td>
      <td class="text-right" style="padding-right: 16px; font-weight: bold;">{{ l('Trays') }}: </td>
      <td style="padding-left: 16px">{{ $order->getTraysLabel($order->planned_quantity, $product->units_per_tray) }}</td>
      <td style="padding-left: 16px">{{ '' }}</td>
      <td> </td>
    </tr>

@endif

  @endforeach

    </tbody>
</table>
</div><!-- div class="panel-body" -->


@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif










