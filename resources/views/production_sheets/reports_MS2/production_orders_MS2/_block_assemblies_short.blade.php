


@if ($sheet->productionorders->whereIn('product_reference', $family['assemblies'])->count())


  @foreach ($sheet->productionorders->whereIn('product_reference', $family['assemblies']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


<div class="tax-summary-wrapper print-friendly text-left">
<table class="order-details tax-summary print-friendly" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th width="14%">{{l('Product Reference')}}</th>
          <th width="50%">{{l('Product Name')}}</th>
          <th width="12%">{{l('Quantity')}}</th>
          <th width="12%">{{l('Unit')}}</th>
          <th width="12%">{{l('Total')}}</th>
        </tr>


    <tr style="font-weight: bold;">
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td> </td>
      <td>{{ $product->measureunit->name }}</td>
      <td>{{ niceQuantity($order->planned_quantity) }}</td>
    </tr>

{{-- Ingredients here: --}}

@foreach ($order->productionorderlines as $line)

    <tr>
      <td style="padding-left: 16px">{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td style="padding-left: 16px">{{ niceQuantity($line->required_quantity / $order->planned_quantity, 3) }}</td>
      <td>{{ $line->product->measureunit->name }}</td>
      <td class="text-right" style="padding-left: 16px">{{ niceQuantity($line->required_quantity) }}</td>
    </tr>

@endforeach


    </tbody>
</table>
</div><!-- div class="panel-body" -->

  @endforeach

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif
