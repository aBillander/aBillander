

@if ($sheet->productionorders->where('work_center_id', $work_center->id)->where('procurement_type', $procurement_type)->count())


<div class="tax-summary-wrapper xprint-friendly text-left">
<table class="order-details tax-summary xprint-friendly" style="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <tbody>
        <tr>
          <th>{{l('Product Reference')}}</th>
          <th>{{l('Product Name')}}</th>
          <th>{{l('Quantity')}}</th>
          <th>{{l('Unit')}}</th>
          <th>{{l('Work Center')}}</th>
        </tr>

  @foreach ($sheet->productionorders->where('procurement_type', $procurement_type) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp
  
    <tr>
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td>{{ $product->as_quantityable($order->planned_quantity) }}</td>
      <td>{{ $product->measureunit->name }}</td>
      <td>{{ $order->workcenter->name ?? '' }}</td>
    </tr>
  @endforeach
    </tbody>
</table>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif
