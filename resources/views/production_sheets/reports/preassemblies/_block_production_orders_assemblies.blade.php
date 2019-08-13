

@if ($sheet->productionorders->where('schedule_sort_order', $schedule_sort_order)->count())


<div class="tax-summary-wrapper xprint-friendly text-left">
<table class="order-details tax-summary xprint-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th>{{l('Product Reference')}}</th>
          <th>{{l('Product Name')}}</th>
          <th>{{l('Quantity')}}</th>
          <th>{{l('Unit')}}</th>
        </tr>
  
  @foreach ($sheet->productionorders->where('schedule_sort_order', $schedule_sort_order) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp

    <tr style="font-weight: bold;">
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td>{{ $product->as_quantityable($order->planned_quantity) }}</td>
      <td>{{ $product->measureunit->name }}</td>
    </tr>

{{-- Ingredients here: --}}

@foreach ($order->productionorderlines as $line)

    <tr>
      <td style="padding-left: 16px">{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td style="padding-left: 16px">{{ $product->as_quantityable($line->required_quantity) }}</td>
      <td>{{ $line->product->measureunit->name }}</td>
    </tr>

@endforeach

    <tr>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
      <td> &nbsp; </td>
    </tr>


  @endforeach
    </tbody>
</table>
</div>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

