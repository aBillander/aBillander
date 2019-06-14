
<div class="panel-body" id="div_packaging_requirements">



@if ($sheet->productionorderlinesGrouped()->count())
<table class="table">
  <thead>
    <tr>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
      <th>{{l('Unit')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionorderlinesGrouped() as $order)
  @php
    $product = \App\Product::with('measureunit')->find( $order['product_id'] );
  @endphp
  @if ( !$product->is_packaging ) @continue @endif
    <tr>
      <td>{{ $order['reference'] }}</td>
      <td>{{ $order['name'] }}</td>
      <td>{{ $sheet->as_quantityable($order['quantity']) }}</td>
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
