

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$qty_10709 = [];
$key_1003_1013 = '1003+1013';
$key_1004_1014 = '1004+1014';

$qty_20601 = [];
// $key_1004_1014 = '1004+1014';

$reference_10709 = $name_10709 = $reference_20001 = $name_20001 = $reference_20601 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}

{{-- 10709 after 1003, 1013, 10710, 10611 --}}

@php

$lines_10709 = $order->productionorderlines->where('reference', '10709');

foreach ($lines_10709 as $line)
{
  if ( array_key_exists( $key_1003_1013, $qty_10709) )
      $qty_10709[$key_1003_1013] += $line->required_quantity;
  else {
      $qty_10709[$key_1003_1013]  = $line->required_quantity;
      $reference_10709 = $line->reference;
      $name_10709 = $line->name;
      $unit_10709 = $line->product->measureunit->sign;
  }
}


$lines_20601 = $order->productionorderlines->where('reference', '20601');

foreach ($lines_20601 as $line)
{
  if ( array_key_exists( $key_1004_1014, $qty_20601) )
      $qty_20601[$key_1004_1014] += $line->required_quantity;
  else {
      $qty_20601[$key_1004_1014]  = $line->required_quantity;
      $reference_20601 = $line->reference;
      $name_20601 = $line->name;
      $unit_20601 = $line->product->measureunit->sign;
  }
}

@endphp

  @endforeach






@if ($sheet->productionorders->whereIn('product_reference', $family['assemblies'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['assemblies']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp



{{-- Ingredients here: --}}

{{-- 10709 after 1000, 1010, 10710 --}}

@php

$lines_10709 = $order->productionorderlines->where('reference', '10709');

foreach ($lines_10709 as $line)
{
  if ($order->product_reference == '10710')
  if ( array_key_exists( $key_1004_1014, $qty_10709) )
      $qty_10709[$key_1004_1014] += $line->required_quantity;
  else
      $qty_10709[$key_1004_1014]  = $line->required_quantity;

}


@endphp



  @endforeach

@endif



<div class="print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="30%">
@if( count($qty_10709) )
            [{{ $reference_10709 }}] {{ $name_10709 }}
@endif
          </th>
          <th width="5%"> </th>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">
@if( count($qty_20601) )
            [{{ $reference_20601 }}] {{ $name_20601 }}
@endif
          </th>
        </tr>

        <tr xstyle="font-weight: bold;">
          <td>
@if( count($qty_10709) )
            {{ $key_1003_1013 }}: {{ niceQuantity($qty_10709[$key_1003_1013]) }} {{ $unit_10709 }}<br />
            {{ $key_1004_1014 }}: {{ niceQuantity($qty_10709[$key_1004_1014]) }} {{ $unit_10709 }}<br />
@endif
          </td>
          <td> </td>
          <td>

          </td>
          <td> </td>
          <td>
@if( count($qty_20601) )
            {{ $key_1004_1014 }}: {{ niceQuantity($qty_20601[$key_1004_1014]) }} {{ $unit_20601 }}
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

{{ abi_r($qty_20601) }}

--}}