

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$key_1006_1016 = '1006+1016';

$qty_20101 = [];
// $key_1006_1016 = '1006+1016';

$reference_20001 = $name_20001 = $reference_20101 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}


@php

$lines_20101 = $order->productionorderlines->where('reference', '20101');

foreach ($lines_20101 as $line)
{
  if ( array_key_exists( $key_1006_1016, $qty_20101) )
      $qty_20101[$key_1006_1016] += $line->required_quantity;
  else {
      $qty_20101[$key_1006_1016]  = $line->required_quantity;
      $reference_20101 = $line->reference;
      $name_20101 = $line->name;
      $unit_20101 = $line->product->measureunit->sign;
  }
}

@endphp

  @endforeach





{{--
@if ($sheet->productionorders->whereIn('product_reference', $family['assemblies'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['assemblies']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp



  @endforeach

@endif
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
@if( count($qty_20101) )
            [{{ $reference_20101 }}] {{ $name_20101 }}
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
@if( count($qty_20101) )
            {{ $key_1006_1016 }}: {{ niceQuantity($qty_20101[$key_1006_1016]) }} {{ $unit_20101 }}
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

{{ abi_r($qty_10709) }}

{{ abi_r($qty_20001) }}

{{ abi_r($qty_20101) }}

--}}