

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$qty_40104 = [];
$key_4022 = '4022';
$key_4023 = '4023';

$qty_20400 = [];
// $key_4022 = '4022';

$qty_20400 = [];
// $key_4023 = '4023';

$all_keys = [$key_4022, $key_4023];

$reference_40104 = $name_40104 = $reference_20400 = $name_20400 = $reference_20400 = $name_20400 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}

{{-- 40104 after 1000, 1010, 10610, 10611 --}}

@php

$lines_40104 = $order->productionorderlines->where('reference', '40104');

foreach ($lines_40104 as $line)
{
    $key = $order->product_reference;

    if ( array_key_exists( $key, $qty_40104) )
        $qty_40104[$key] += $line->required_quantity;
    else {
        $qty_40104[$key]  = $line->required_quantity;
        $reference_40104 = $line->reference;
        $name_40104 = $line->name;
        $unit_40104 = $line->product->measureunit->sign;
    }
}


$lines_20400 = $order->productionorderlines->where('reference', '20400');

foreach ($lines_20400 as $line)
{
  if ( array_key_exists( $key_4023, $qty_20400) )
      $qty_20400[$key_4023] += $line->required_quantity;
  else {
      $qty_20400[$key_4023]  = $line->required_quantity;
      $reference_20400 = $line->reference;
      $name_20400 = $line->name;
      $unit_20400 = $line->product->measureunit->sign;
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

{{-- 40104 after 40105 --}}

@php

$lines_40104 = $order->productionorderlines->where('reference', '40104');

foreach ($lines_40104 as $line)
{
  
  if ( array_key_exists( $key_4022, $qty_40104) )
      $qty_40104[$key_4022] += $line->required_quantity;
  else
      $qty_40104[$key_4022]  = $line->required_quantity;

}


$lines_20400 = $order->productionorderlines->where('reference', '20400');

foreach ($lines_20400 as $line)
{
  if ( array_key_exists( $key_4022, $qty_20400) )
      $qty_20400[$key_4022] += $line->required_quantity;
  else {
      $qty_20400[$key_4022]  = $line->required_quantity;
      $reference_20400 = $line->reference;
      $name_20400 = $line->name;
      $unit_20400 = $line->product->measureunit->sign;
  }
}


@endphp



  @endforeach

@endif



<div class="print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="30%">
@if( count($qty_40104) )
            [{{ $reference_40104 }}] {{ $name_40104 }}
@endif
          </th>
          <th width="5%"> </th>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">
@if( count($qty_20400) )
            [{{ $reference_20400 }}] {{ $name_20400 }}
@endif
          </th>
        </tr>

        <tr xstyle="font-weight: bold;">
          <td>
@if( count($qty_40104) )
            {{ $key_4022 }}: {{ niceQuantity($qty_40104[$key_4022] ?? '') }} {{ $unit_40104 }}<br />
            {{ $key_4023 }}: {{ niceQuantity($qty_40104[$key_4023] ?? '') }} {{ $unit_40104 }}<br />
@endif
          </td>
          <td> </td>
          <td>

          </td>
          <td> </td>
          <td>
@if( count($qty_20400) )
            {{ $key_4022 }}: {{ niceQuantity($qty_20400[$key_4022] ?? '') }} {{ $unit_20400 }}
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

{{ abi_r($qty_20400) }}

{{ abi_r($qty_20400) }}

--}}