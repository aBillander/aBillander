

@php

$qty_10601 = [];
$key_1000_1010 = '1000+1010';
$key_1001_1011 = '1001+1011';
$key_1002_1012 = '1002+1012';

$qty_20001 = [];
// $key_1001_1011 = '1001+1011';

$qty_20601 = [];
// $key_1002_1012 = '1002+1012';

$reference_10601 = $name_10601 = $reference_20001 = $name_20001 = $reference_20601 = $name_20601 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


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
      $unit_10601 = $line->product->measureunit->sign;
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
      $unit_20001 = $line->product->measureunit->sign;
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

@endif



<div class="print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="30%">
@if( count($qty_10601) )
            [{{ $reference_10601 }}] {{ $name_10601 }}
@endif
          </th>
          <th width="5%"> </th>
          <th width="30%">
@if( count($qty_20001) )
            [{{ $reference_20001 }}] {{ $name_20001 }}
@endif
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
@if( count($qty_10601) )
            {{ $key_1000_1010 }}: {{ niceQuantity($qty_10601[$key_1000_1010]) }} {{ $unit_10601 }}<br />
            {{ $key_1001_1011 }}: {{ niceQuantity($qty_10601[$key_1001_1011]) }} {{ $unit_10601 }}<br />
            {{ $key_1002_1012 }}: {{ niceQuantity($qty_10601[$key_1002_1012]) }} {{ $unit_10601 }}<br />
@endif
          </td>
          <td> </td>
          <td>
@if( count($qty_20001) )
            {{ $key_1001_1011 }}: {{ niceQuantity($qty_20001[$key_1001_1011]) }} {{ $unit_20001 }}
@endif
          </td>
          <td> </td>
          <td>
@if( count($qty_20601) )
            {{ $key_1002_1012 }}: {{ niceQuantity($qty_20601[$key_1002_1012]) }} {{ $unit_20601 }}
@endif
          </td>
        </tr>

    </tbody>
</table>
</div><!-- div class="panel-body" -->

@else

@endif


{{--

{{ abi_r($qty_10601) }}

{{ abi_r($qty_20001) }}

{{ abi_r($qty_20601) }}

--}}