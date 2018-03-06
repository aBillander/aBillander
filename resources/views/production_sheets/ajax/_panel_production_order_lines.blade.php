
 <div class="modal-header">

     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

     <h4 class="modal-title" id="modalProductionOrderShowLabel">{{ l('Production Order') }}: <span id="production_order_id">{{ $order->id }}</span> :: [{{ $order->product_reference }}] {{ $order->product_name }}</h4>

 </div>


<div class="modal-body">
<div class="panel-body" id="div_production_order_lines">
   <h3 style="color: #e95420; text-decoration: none;">{{l('Materials')}}</h3>

   <div class="table-responsive">


@if ($order->productionorderlines->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Product ID')}}</th>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
      <th>{{l('Measure Unit')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($order->productionorderlines as $line)
    <tr>
      <td>{{ $line->id }}</td>
      <td>{{ $line->product_id }}</td>
      <td>{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td>{{ $line->required_quantity }}</td>
      <td>{{ $line->product->measureunit->name }}</td>
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
</div>

<!-- div class="panel-footer text-right">
  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button>
</div -->
