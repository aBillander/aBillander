

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])


@php

$key_1005_1015 = '1005+1015';

$qty_20100 = [];
$qty_20003 = [];
// $key_1005_1015 = '1005+1015';

$reference_20001 = $name_20001 = $reference_20100 = $reference_20003 ='';

@endphp


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


{{-- Ingredients here: --}}


@php

$lines_20100 = $order->productionorderlines->where('reference', '20100');

foreach ($lines_20100 as $line)
{
  if ( array_key_exists( $key_1005_1015, $qty_20100) )
      $qty_20100[$key_1005_1015] += $line->required_quantity;
  else {
      $qty_20100[$key_1005_1015]  = $line->required_quantity;
      $reference_20100 = $line->reference;
      $name_20100 = $line->name;
      $unit_20100 = $line->product->measureunit->sign;
  }
}

$lines_20003 = $order->productionorderlines->where('reference', '20003');

foreach ($lines_20003 as $line)
{
  if ( array_key_exists( $key_1005_1015, $qty_20003) )
      $qty_20003[$key_1005_1015] += $line->required_quantity;
  else {
      $qty_20003[$key_1005_1015]  = $line->required_quantity;
      $reference_20003 = $line->reference;
      $name_20003 = $line->name;
      $unit_20003 = $line->product->measureunit->sign;
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
@if( count($qty_20003) )
            [{{ $reference_20003 }}] {{ $name_20003 }}
@endif
          </th>
          <th width="5%"> </th>
          <th width="30%">
@if( count($qty_20100) )
            [{{ $reference_20100 }}] {{ $name_20100 }}
@endif
          </th>
        </tr>

        <tr xstyle="font-weight: bold;">
          <td>

          </td>
          <td> </td>
          <td>
@if( count($qty_20003) )
            {{ $key_1005_1015 }}: {{ niceQuantity($qty_20003[$key_1005_1015]) }} {{ $unit_20003 }}
@endif
          </td>
          <td> </td>
          <td>
@if( count($qty_20100) )
            {{ $key_1005_1015 }}: {{ niceQuantity($qty_20100[$key_1005_1015]) }} {{ $unit_20100 }}
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

{{ abi_r($qty_20100) }}

--}}