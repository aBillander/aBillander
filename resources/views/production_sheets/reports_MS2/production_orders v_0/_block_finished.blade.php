
<div class="panel-body" id="div_production_orders">


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())
<table class="table" xstyle="border:0px #ffffff">
  <thead>
    <tr>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
      <th>{{l('Unit')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp
  
    <tr>
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td>{{ $product->as_quantityable($order->planned_quantity) }}</td>
      <td>{{ $product->measureunit->name }}</td>
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



</div><!-- div class="panel-body" -->
