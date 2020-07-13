
@php

// Long life to Gorrino Style!!!

$pani_tools = ['90000', '90001', '90100', ];

@endphp

@if ($sheet->productionordertoollinesGrouped()->whereIn('reference', $pani_tools)->count())


<div class="xtax-summary-wrapper xprint-friendly text-left">
<table class="order-details tax-summary xprint-friendly" xstyle="margin-bottom: 0mm;" xstyle="border: 1px #ccc solid">
      <thead>
        <tr>
          <th>{{l('Tool Reference')}}</th>
          <th>{{l('Tool Name')}}</th>
          <th>{{l('Quantity')}}</th>
          <th>{{l('Location')}}</th>
        </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionordertoollinesGrouped()->whereIn('reference', $pani_tools) as $order)

    <tr>
      <td>{{ $order['reference'] }}</td>
      <td>{{ $order['name'] }}</td>
      <td>{{ number_format( $order['quantity'], 0, '', '' ) }}</td>
      <td>{{ $order['location'] }}</td>
    </tr>
  @endforeach
    </tbody>
</table>
</div>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

