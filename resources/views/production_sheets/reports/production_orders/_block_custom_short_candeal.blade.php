

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

// Long life to Gorrino Style!!!

$candeal_dough = ['10500', '10510'];
$candeal_dough_id = [
          \App\Models\Product::where('reference', '10500')->first(),
          \App\Models\Product::where('reference', '10510')->first(),
];

// Force-brute strategy:

$candeal_dough_qty = [];
$candeal_dough_qty['10500'] = $candeal_dough_qty['10510'] = 0.0;

foreach ($sheet->productionorders->where('procurement_type', 'manufacture')->whereIn('product_reference', $family['references']) as $order)
{
    // $product = $order->product;
    $product = \App\Models\Product::find( $order->product_id );
    $candeal_dough_qty['10500'] += $product->getChildProductQuantity( $candeal_dough_id[0]->id, $order->planned_quantity );
    $candeal_dough_qty['10510'] += $product->getChildProductQuantity( $candeal_dough_id[1]->id, $order->planned_quantity );

    // abi_r($product->reference.' - '.$candeal_dough_qty['10500'].' - '.$candeal_dough_qty['10510']);
    // abi_r($candeal_dough_id[0]->id.' - '.$candeal_dough_id[1]->id);
}

// die();
@endphp







@if ( 1 && $sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())

{{--
  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Models\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --} }

{{-- 20001 after 4021 --} }

@php

$lines_10500 = $order->productionorderlines->where('reference', '10500');

foreach ($lines_10500 as $line)
{
  if ( array_key_exists( $key_4021, $qty_20001) )
      $qty_20001[$key_4021] += $line->required_quantity;
  else {
      $qty_20001[$key_4021]  = $line->required_quantity;
      $reference_20001 = $line->reference;
      $name_20001 = $line->name;
      $unit_20001 = $line->product->measureunit->sign;
  }
}

@endphp

  @endforeach
--}}


<div class="print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">
@if( $candeal_dough_qty['10500'] != 0.0 )
            [{{ $candeal_dough_id[0]->reference }}] {{ $candeal_dough_id[0]->name }}
@endif
          </th>
        </tr>

        <tr xstyle="font-weight: bold;">
          <td>

          </td>
          <td> </td>
          <td>

          </td>
          <td> </td>
          <td>
@if( $candeal_dough_qty['10500'] != 0.0 )
            {{ niceQuantity($candeal_dough_qty['10500'] ?? '') }} {{ $candeal_dough_id[0]->measureunit->name }}
@endif
          </td>
        </tr>







        <tr>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">
@if( $candeal_dough_qty['10510'] != 0.0 )
            [{{ $candeal_dough_id[0]->reference }}] {{ $candeal_dough_id[0]->name }}
@endif
          </th>
        </tr>

        <tr xstyle="font-weight: bold;">
          <td>

          </td>
          <td> </td>
          <td>

          </td>
          <td> </td>
          <td>
@if( $candeal_dough_qty['10510'] != 0.0 )
            {{ niceQuantity($candeal_dough_qty['10510'] ?? '') }} {{ $candeal_dough_id[0]->measureunit->name }}
@endif
          </td>
        </tr>

    </tbody>
</table>
</div><!-- div class="panel-body" -->

@else

@endif


        <div style="margin-bottom: 0px">&nbsp;</div>


{{--

{{ abi_r($qty_40104) }}

{{ abi_r($qty_20001) }}

{{ abi_r($qty_20001) }}

--}}