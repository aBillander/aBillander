

   <div class="table-responsive">


@if ($sheet->productionrequirements->count())

<table id="sheets" class="table table-hover">
    <thead>
        <tr>
	      <th>{{l('ID', [], 'layouts')}}</th>
	      <!-- th>{{l('Product ID')}}</th -->
	      <th>{{l('Product Reference')}}</th>
	      <th>{{l('Product Name')}}</th>
	      <th>{{l('Manufacturing Batch Size')}}</th>
	      <th>{{l('Quantity')}}</th>
          <th>{{l('Number of Batches')}}</th>
	      <!-- th>{{l('Provenience')}}</th>
	      <th>{{l('Status', 'layouts')}}</th>
	      <th class="text-center">{{l('Notes', [], 'layouts')}}</th -->
	      <th class="text-right button-pad"> </th>
	    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionrequirements as $line)
    <tr>

      <td>{{ $line->id }}</td>
      <td><a href="{{ URL::to('products/' . $line->product->id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->product->reference }}</a></td>
      <td>{{ $line->product->name }}</td>
      <td>{{ $line->as_quantityable($line->manufacturing_batch_size, 0) }}</td>
      <td>{{ $line->product->as_quantityable($line->required_quantity) }}</td>
      <td class="text-center">{{ $line->as_quantityable($line->required_quantity / $line->manufacturing_batch_size, 0) }}
        &nbsp; ( x {{ $line->as_quantityable($line->manufacturing_batch_size, 0) }} = {{ $line->product->as_quantityable($line->required_quantity) }} )
      <td>
{{--
                <a class="btn btn-sm btn-warning " href="{{ URL::to('productionorders/' . $order->id . '/edit') }}"  title="{{l('Edit', [], 'layouts')}}" target="_productionorder"><i class="fa fa-pencil"></i></a>
--}}
                <a class="btn btn-sm btn-danger delete-production-requirement" data-id="{{$line->id}}" title="{{l('Delete', [], 'layouts')}}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '('.$line->id.') ['.$line->reference.'] '.$line->name }}" 
                        onClick="return false;"><i class="fa fa-trash-o"></i></a>

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

