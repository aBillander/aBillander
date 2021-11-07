

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ route('lot.stockmovements.export', [$lot->id] + Request::all()) }}" class="btn xbtn-sm btn-grey" style="margin-right: 32px;"  
                title="{{l('Export', [], 'layouts')}}" onclick="alert('You naughty, naughty!'); return false;"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>{{-- see: warehouses/indexProducts.blade.php --}}

        <a href="{{ URL::to('lots') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Lots') }}</a>


    </div>
    <h2>
        {{ l('Lot Stock Allocations') }} <span style="color: #cccccc;">::</span> <a href="{{ route( 'lots.edit', $lot->id ) }}" title="{{l('Go to', [], 'layouts')}}">{{ $lot->reference }}</a>   <span class="badge" style="background-color: #3a87ad;" title="{{ optional($lot->measureunit)->name }}"> &nbsp; {{ optional($lot->measureunit)->sign }} &nbsp; </span>
    </h2>        
</div>


<div id="div_stockallocations">
   <div class="table-responsive">

@if ($stockallocations->count())
<table id="stockallocations" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th style="text-transform: none;">{{l('Date')}}
              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Document Date') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
          	</th>
            <th style="text-transform: none;">{{l('Document')}}</th>
            <th style="text-transform: none;">{{l('Customer')}}</th>
			<th>{{l('Quantity')}}</th>
{{--
			<th>{{l('is_reservation')}}</th>
			<th>{{l('lotable_id')}}</th>
			<th>{{l('lotable_type')}}</th>
			<th>{{l('created_at')}}</th>
			<th>{{l('updated_at')}}</th>
--}}
{{--
            <th style="text-transform: none;">{{l('Customer Final Price')}}</th>
--}}
			<th> </th>
		</tr>
	</thead>
	<tbody>

	@foreach ($stockallocations as $stockallocation)

@php

// abi_r($stockallocation->lotable, true);

$document = optional($stockallocation->lotable)->document;

@endphp
		<tr>
			<td>{{ $stockallocation->id }}</td>
			<td>{{ abi_date_short( $document->document_date ) }}</td>
			<td>

@if ( $route = $stockallocation->getLotableDocumentRoute() )
{{-- optional(optional($stockmovement->stockmovementable)->document)->id --} }
        <!-- a href="{{ route($route.'.edit', ['0']).'?document_reference='.$stockmovement->document_reference }}" title="{{l('Open Document', [], 'layouts')}}" target="_new" -->  --}}

    @if ( optional(optional($stockallocation->lotable)->document)->id ) 
        <a href="{{ route($route.'.edit', [optional(optional($stockallocation->lotable)->document)->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ optional(optional($stockallocation->lotable)->document)->document_reference }}</a>
    @else
        <i class="fa fa-exclamation-triangle btn-xs btn-danger" title="{{l('Document ID not found', 'layouts')}} &#013;&#010; - lotable_id: {{ $stockallocation->lotable_id }} &#013;&#010; - lotable_type: {{ $stockallocation->lotable_type }}"></i>
    @endif
@else
      -
@endif


				{{-- $stockallocation->getLotableDocumentRoute() }}/{{ optional(optional($stockallocation->lotable)->document)->id --}}

			</td>
            <td>
@if( $document->customer_id )
                <a href="{{ route('customers.edit', [$document->customer->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_new">
                        {{ $document->customer->name_commercial }}
                </a>
@elseif ( $document->warehouse_id )
                {{ optional($document->warehouse)->getAliasNameAttribute() ?? '-' }}
@endif
            </td>
			<td>{{ $lot->measureunit->quantityable( $stockallocation->quantity ) }}</td>
{{--
			<td>{{ $stockallocation->is_reservation }}</td>
			<td>{{ $stockallocation->lotable_id }}</td>
			<td>{{ $stockallocation->lotable_type }}</td>
			<td>{{ $stockallocation->created_at }}</td>
			<td>{{ $stockallocation->updated_at }}</td>
--}}
{{--
            <td>{{ optional($stockallocation->lotable)->as_price('unit_customer_final_price') }}</td>
--}}

            <td class="text-right">
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('lotitems/' . $stockallocation->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Lot Stock Allocations') }} ::  ({{$stockallocation->id}}) {{ $stockallocation->created_at }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

{{--
                @if (  is_null($stockallocation->deleted_at))
                <!-- a class="btn btn-sm btn-success" href="{{ URL::to('stockmovements/' . $stockmovement->id) }}" title=" Ver "><i class="fa fa-eye"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id . '/edit') }}" title=" Modificar "><i class="fa fa-pencil"></i></a -->
                <!-- a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('stockmovements/' . $stockmovement->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Stock Movements') }} ::  ({{$stockmovement->id}}) {{ $stockmovement->date }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->
                @else
                <a class="btn btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
--}}
			</td>
		</tr>
	@endforeach

	</tbody>
</table>
{{-- !! $stockallocations->appends( Request::all() )->render() !! --}} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $stockallocations->count() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>
