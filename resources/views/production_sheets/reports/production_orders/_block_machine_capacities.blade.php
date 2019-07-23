


@php

  $capacities = $order->getMachineCapacityList();

  $machine_small = isset( $capacities[0] ) ? $capacities[0] : 0.0;
  $machine_big   = isset( $capacities[1] ) ? $capacities[1] : 0.0;

  $loads_small = $order->getMachineLoads( $order->planned_quantity, $machine_small);
  $loads_big   = $order->getMachineLoads( $order->planned_quantity, $machine_big  );

@endphp

    <tr xstyle="font-weight: bold;">
      <td> </td>
      <td class="text-right" style="padding-right: 16px; font-weight: bold;">{{ l('Carga Amasadoras') }}: </td>
      <td style="padding-left: 16px">{{ $loads_small }}</td>
      <td style="padding-left: 16px">{{ $loads_big   }}</td>
      <td> </td>
    </tr>


{{-- Ingredients here: --}}

@foreach ($order->productionorderlines as $line)

    <tr>
      <td style="padding-left: 16px">{{ $line->reference }}</td>
      <td><span style="float: right">[{{ niceQuantity($line->required_quantity, $line->product->measureunit->decimalPlaces) }}]</span>{{ $line->name }}</td>
      <td class="text-right" style="padding-right: 16px">
      	{{ niceQuantity($line->required_quantity / $loads_small, $line->product->measureunit->decimalPlaces) }}</td>
      <td class="text-right" style="padding-right: 16px">
      	{{ niceQuantity($line->required_quantity / $loads_big, $line->product->measureunit->decimalPlaces) }}</td>
      <td class="text-right" style="padding-right: 16px">{{ niceQuantity($line->bom_line_quantity, $line->product->measureunit->decimalPlaces) }}</td>
    </tr>

@endforeach