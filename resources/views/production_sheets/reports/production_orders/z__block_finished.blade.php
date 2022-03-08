
<div class="panel-body" id="div_production_orders">


@if ($sheet->productionorders->where('work_center_id', $work_center->id)->whereIn('product_reference', $family['references'])->count())


<table class="table">
    <thead>
     <tr>
      <th width="14%">{{l('Product Reference')}}</th>
      <th width="50%">{{l('Product Name')}}</th>
      <th width="12%">{{l('Quantity')}}</th>
      <th width="12%">{{l('Unit')}}</th>
      <th width="12%">{{l('Total')}}</th>
    </tr>
  </thead>
  <tbody>

    </tbody>
</table>

  @foreach ($sheet->productionorders->whereIn('product_reference', $family['references']) as $order)
  @php
    $product = \App\Product::find( $order->product_id );
  @endphp


      @include('production_sheets.reports.production_orders._chunk_production_order', ['with_header' => 0])


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
