
<table class="table print-friendly text-left">
@if ( $with_header )
    <thead>
     <tr>
      <th width="14%">{{l('Product Reference')}} xx</th>
      <th width="50%">{{l('Product Name')}}</th>
      <th width="12%">{{l('Quantity')}}</th>
      <th width="12%">{{l('Unit')}}</th>
      <th width="12%">{{l('Total')}}</th>
    </tr>
  </thead>
@else
    <thead>
     <tr>
      <th width="14%"> </th>
      <th width="50%"> </th>
      <th width="12%"> </th>
      <th width="12%"> </th>
      <th width="12%"> </th>
    </tr>
  </thead>
@endif
  <tbody>

    <tr style="font-weight: bold;">
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td> </td>
      <td>{{ $product->measureunit->name }}</td>
      <td>{{ niceQuantity($order->planned_quantity) }}</td>
    </tr>

{{-- Ingredients here: --}}

@foreach ($order->productionorderlines as $line)

@if ( $line->product->is_packaging && !$with_packaging )
@continue
@endif

    <tr>
      <td style="padding-left: 16px">{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td style="padding-left: 16px">{{ niceQuantity($line->required_quantity / $order->planned_quantity, 3) }}</td>
      <td>{{ $line->product->measureunit->name }}</td>
      <td class="text-right" style="padding-left: 16px">{{ niceQuantity($line->required_quantity) }}</td>
    </tr>

@endforeach

    <tr>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
    </tr>

    </tbody>
</table>
