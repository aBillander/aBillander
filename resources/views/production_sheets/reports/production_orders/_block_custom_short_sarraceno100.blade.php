

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$qty_20102 = [];
$key_4024 = '4024';

$reference_40104 = $name_40104 = $reference_20102 = $name_20102 = $reference_20102 = $name_20102 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Models\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}

{{-- 20102 after 4024 --}}

@php

$lines_20102 = $order->productionorderlines->where('reference', '20102');

foreach ($lines_20102 as $line)
{
  if ( array_key_exists( $key_4024, $qty_20102) )
      $qty_20102[$key_4024] += $line->required_quantity;
  else {
      $qty_20102[$key_4024]  = $line->required_quantity;
      $reference_20102 = $line->reference;
      $name_20102 = $line->name;
      $unit_20102 = $line->product->measureunit->sign;
  }
}

@endphp

  @endforeach



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
@if( count($qty_20102) )
            [{{ $reference_20102 }}] {{ $name_20102 }}
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
@if( count($qty_20102) )
            {{ $key_4024 }}: {{ niceQuantity($qty_20102[$key_4024] ?? '') }} {{ $unit_20102 }}
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

{{ abi_r($qty_20102) }}

{{ abi_r($qty_20102) }}

--}}