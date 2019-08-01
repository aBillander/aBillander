

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$qty_20001 = [];
$key_4021 = '4021';

$reference_40104 = $name_40104 = $reference_20001 = $name_20001 = $reference_20001 = $name_20001 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}

{{-- 20001 after 4021 --}}

@php

$lines_20001 = $order->productionorderlines->where('reference', '20001');

foreach ($lines_20001 as $line)
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
@if( count($qty_20001) )
            [{{ $reference_20001 }}] {{ $name_20001 }}
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
@if( count($qty_20001) )
            {{ $key_4021 }}: {{ niceQuantity($qty_20001[$key_4021] ?? '') }} {{ $unit_20001 }}
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