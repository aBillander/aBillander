
<div class="panel-body" id="div_tool_requirements">


@if ($sheet->productionordertoollinesGrouped()->count())
<table class="table">
  <thead>
    <tr>
      <th>{{l('Tool Reference')}}</th>
      <th>{{l('Tool Name')}}</th>
      <th>{{l('Quantity')}}</th>
      <th>{{l('Location')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionordertoollinesGrouped() as $order)

    <tr>
      <td>{{ $order['reference'] }}</td>
      <td>{{ $order['name'] }}</td>
      <td>{{ number_format( $order['quantity'], 0, '', '' ) }}</td>
      <td>{{ $order['location'] }}</td>
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
