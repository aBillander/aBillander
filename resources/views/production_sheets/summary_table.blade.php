@extends('layouts.master')

@section('title') {{ l('Production Sheets - Summary Table') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

        <a href="{{ URL::to('productionsheets/'.$sheet->id) }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Production Sheet') }}</a>
    </div>
    <h2>
        <i class="fa fa-table"></i> <a href="{{ URL::to('productionsheets/'.$sheet->id) }}">{{ l('Production Sheets') }}</a> <span style="color: #cccccc;">::</span> <span class="lead well well-sm">{{ abi_date_form_short($sheet->due_date) }}</span> <span style="color: #cccccc;">::</span> {{ $work_center->name }}
    </h2>        
</div>

    </div>
</div> 


<div id="div_sheets">
   <div class="table-responsive">

@if ($sheet->customerorders()->count())
<table id="sheets" class="table table-hover">
  <thead>
    <tr>
      <th> </th>
      <th> </th>
			@foreach ($columns as $col)
      <th>{{ $col['reference'] }} - {{ $col['name'] }}</th>
      @endforeach
		</tr>
	</thead>
	<tbody>


{{-- Rows --}}

	@foreach ($sheet->customerorders as $order)
		<tr>
			<td style="font-weight: bold;">[{{ $order->document_reference }}] {{ $order->customer->name_commercial }}</td>
      <td style="font-weight: bold;">
          @if ( $order->hasShippingAddress() )
              {{ $order->shippingaddress->alias }} 
          @endif
      </td>

@php
        $lines = $order->customerorderlines
                    ->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, [
                        'product_id' => $group->first()->product_id,
                        'quantity' => $group->sum('quantity'),
                      ]);
                    }, collect());
@endphp

{{--
        @foreach ($lines as $line)
           <td class="text-center">
             @if ( $columns->contains('product_id', $line['product_id']) )
               { {-- $sheet->as_quantityable($line['quantity']) --} }
               {{ $line['quantity'] }}
             @endif
           </td>
        @endforeach
--}}

      @foreach ($columns as $col)
        <td class="text-center">
          @if ( $lines->contains('product_id', $col['product_id']) )
            {{ $lines->get($col['product_id'])['quantity'] }}
          @endif
        </td>
      @endforeach

		</tr>
	@endforeach


{{-- Totals --}}

    <tr class="info">
      <td> </td>
      <td class="text-center"><strong>{{ l('Total', 'layouts') }}</strong></td>

      @foreach ($columns as $col)
        <td class="text-center">
          {{ $col['quantity'] }}
        </td>
      @endforeach

    </tr>


{{-- Repeat header --}}

    <tr>
      <th> </th>
      <th> </th>
      @foreach ($columns as $col)
      <th>{{ $col['reference'] }} - {{ $col['name'] }}</th>
      @endforeach
    </tr>

{{-- --}}

    </tbody>
</table>

{{-- abi_r($columns) --}}

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection


@section('scripts') @parent 

<!-- script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script -->

@endsection
