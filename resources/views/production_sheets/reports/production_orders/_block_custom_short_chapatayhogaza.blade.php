

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'navicon', 'section_name' => l('Otros')])



@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


{{-- 10700 after 1100, 10706, 10707 --}}

@php
  $qty_10700 = [];

  $orders = $sheet->productionorders->whereIn('product_reference', ['1100', '10706', '10707'])
@endphp

@foreach ($orders as $order)

@php
$lines_10700 = $order->productionorderlines->where('reference', '10700');

    foreach ($lines_10700 as $line)
    {
      if ( array_key_exists( $order->product_reference, $qty_10700) )
          $qty_10700[$order->product_reference] += $line->required_quantity;
      else {
          $qty_10700[$order->product_reference]  = $line->required_quantity;
          $reference_10700 = $line->reference;
          $name_10700 = $line->name;
          $unit_10700 = $line->product->measureunit->sign;
      }
    }
@endphp

@endforeach


{{-- 10703 after 10708 --}}

@php
  $qty_10703 = [];

  $orders = $sheet->productionorders->whereIn('product_reference', ['10708'])
@endphp

@foreach ($orders as $order)

@php
$lines_10703 = $order->productionorderlines->where('reference', '10703');

    foreach ($lines_10703 as $line)
    {
      if ( array_key_exists( $order->product_reference, $qty_10703) )
          $qty_10703[$order->product_reference] += $line->required_quantity;
      else {
          $qty_10703[$order->product_reference]  = $line->required_quantity;
          $reference_10703 = $line->reference;
          $name_10703 = $line->name;
          $unit_10703 = $line->product->measureunit->sign;
      }
    }
@endphp

@endforeach


{{--  --}}



<div class="print-friendly text-left">
<table class="order-details tax-summary print-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="30%">
@if( count($qty_10700) )
            [{{ $reference_10700 }}] {{ $name_10700 }}
@endif
          </th>
          <th width="5%"> </th>
          <th width="30%">

          </th>
          <th width="5%"> </th>
          <th width="30%">
@if( count($qty_10703) )
            [{{ $reference_10703 }}] {{ $name_10703 }}
@endif
          </th>
        </tr>

        <tr xstyle="font-weight: bold;">
          <td>
@if( count($qty_10700) )
      @foreach ($qty_10700 as $key => $qty)
            {{ $key }}: {{ niceQuantity($qty) }} {{ $unit_10700 }}<br />
      @endforeach
@endif
          </td>
          <td> </td>
          <td>

          </td>
          <td> </td>
          <td>
@if( count($qty_10703) )
      @foreach ($qty_10703 as $key => $qty)
            {{ $key }}: {{ niceQuantity($qty) }} {{ $unit_10703 }}<br />
      @endforeach
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