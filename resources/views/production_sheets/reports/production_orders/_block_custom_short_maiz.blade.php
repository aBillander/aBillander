

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$qty_40102 = [];
$key_4002 = '4002';
$key_4012 = '4012';
$key_4006 = '4006';

$qty_20001 = [];
// $key_4012 = '4012';

$qty_20001 = [];
// $key_4006 = '4006';

$all_keys = [$key_4002, $key_4012, $key_4006];

$reference_40102 = $name_40102 = $reference_20001 = $name_20001 = $reference_20001 = $name_20001 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Models\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}

{{-- 40102 after 1000, 1010, 10610, 10611 --}}

@php

$lines_40102 = $order->productionorderlines->where('reference', '40102');

foreach ($lines_40102 as $line)
{
    $key = $order->product_reference;

    if ( array_key_exists( $key, $qty_40102) )
        $qty_40102[$key] += $line->required_quantity;
    else {
        $qty_40102[$key]  = $line->required_quantity;
        $reference_40102 = $line->reference;
        $name_40102 = $line->name;
        $unit_40102 = $line->product->measureunit->sign;
    }
}


$lines_20001 = $order->productionorderlines->where('reference', '20001');

foreach ($lines_20001 as $line)
{
  if ( array_key_exists( $key_4006, $qty_20001) )
      $qty_20001[$key_4006] += $line->required_quantity;
  else {
      $qty_20001[$key_4006]  = $line->required_quantity;
      $reference_20001 = $line->reference;
      $name_20001 = $line->name;
      $unit_20001 = $line->product->measureunit->sign;
  }
}

@endphp

  @endforeach






@if ($sheet->productionorders->whereIn('product_reference', $family['assemblies'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['assemblies']) as $order)
  @php
    $product = \App\Models\Product::find( $order->product_id );
  @endphp



{{-- Ingredients here: --}}

{{-- 40102 after 41102 --}}

@php

$lines_40102 = $order->productionorderlines->where('reference', '40102');

foreach ($lines_40102 as $line)
{
  
  if ( array_key_exists( $key_4002, $qty_40102) )
      $qty_40102[$key_4002] += $line->required_quantity;
  else
      $qty_40102[$key_4002]  = $line->required_quantity;

}


@endphp



  @endforeach

@endif



<div class="print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="30%">
@if( count($qty_40102) )
            [{{ $reference_40102 }}] {{ $name_40102 }}
@endif
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
@if( count($qty_40102) )
            {{ $key_4002 }}: {{ niceQuantity($qty_40102[$key_4002] ?? '') }} {{ $unit_40102 }}<br />
            {{ $key_4012 }}: {{ niceQuantity($qty_40102[$key_4012] ?? '') }} {{ $unit_40102 }}<br />
            {{ $key_4006 }}: {{ niceQuantity($qty_40102[$key_4006] ?? '') }} {{ $unit_40102 }}<br />
@endif
          </td>
          <td> </td>
          <td>

          </td>
          <td> </td>
          <td>
@if( count($qty_20001) )
            {{ $key_4006 }}: {{ niceQuantity($qty_20001[$key_4006] ?? '') }} {{ $unit_20001 }}
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

{{ abi_r($qty_40102) }}

{{ abi_r($qty_20001) }}

{{ abi_r($qty_20001) }}

--}}