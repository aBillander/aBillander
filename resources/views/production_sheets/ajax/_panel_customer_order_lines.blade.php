
<div class="panel-body" id="div_customer_order_lines">
   <div class="table-responsive">


@if ($order->customerorderlines->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Product ID')}}</th>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($order->customerorderlines as $line)
    <tr>
      <td>{{ $line->id }}</td>
      <td>{{ $line->product_id }}</td>
      <td>{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td>{{ $line->quantity }}</td>
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

<!-- div class="panel-footer text-right">
  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button>
</div -->
