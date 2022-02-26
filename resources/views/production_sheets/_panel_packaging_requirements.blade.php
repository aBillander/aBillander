
<div class="panel-body" id="div_packaging_requirements">
   <div class="table-responsive">


@if ($sheet->productionorderlinesGrouped()->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <!-- th>{{l('ID', [], 'layouts')}}</th -->
      <th>{{l('Product ID')}}</th>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
      <th>{{l('Measure Unit')}}</th>
      <th>{{l('Stock on hand')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionorderlinesGrouped() as $order)
  @php
    $product = \App\Product::with('measureunit')->find( $order['product_id'] );
  @endphp
  @if ( !$product->is_packaging ) @continue @endif
    <tr>
      <td>{{ $order['product_id'] }}</td>
      <td>{{ $order['reference'] }}</td>
      <td>{{ $order['name'] }}</td>
      <td>{{ $sheet->as_quantityable($order['quantity']) }}</td>
      <td>{{ $product->measureunit->name }}</td>
      <td>{{ $product->as_quantity('quantity_onhand') }}</td>

@if( ($qty = $product->quantity_onhand - $order['quantity']) < 0.0 )
           <td class="text-right alert-danger" style="width:1px; white-space: nowrap;">
            {{ $product->as_quantityable( $qty ) }}
@else
           <td class="text-right" style="width:1px; white-space: nowrap;">
@endif

                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('productionsheets/' . $sheet->id . '/show') }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('sheets/' . $sheet->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('sheets/' . $sheet->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Production Sheets') }} :: ({{$sheet->id}}) {{ $sheet->name }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->

            </td>
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

   </div>
</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">
  <!-- a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a -->
  <!-- button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-plus"></i>
     &nbsp; {{ l('Add Production Order') }}
  </button -->
</div>
