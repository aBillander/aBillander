
<div class="panel-body" id="div_not_scheduled_products">
   <div class="table-responsive">


@if ($sheet->productsNotScheduled()->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <!-- th>{{l('ID', [], 'layouts')}}</th -->
      <th>{{l('Product ID')}}</th>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Falta')}}</th>
      <th>{{l('Sobra')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productsNotScheduled() as $order)
    <tr>
      <td>{{ $order['product_id'] }}</td>
      <td>{{ $order['reference'] }}</td>
      <td>{{ $order['name'] }}</td>
@if ($order['quantity']>0)
      <td>{{ $order['quantity'] }}</td><td></td>
@else
      <td></td><td>{{ -$order['quantity'] }}</td>
@endif
           <td class="text-right" style="width:1px; white-space: nowrap;">

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
