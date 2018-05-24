@extends('layouts.master')

@section('title') {{ l('Stock Movements') }} @parent @stop


@section('content')



<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('stockmovements/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Stock Movements') }}
    </h2>        
</div>

<div id="div_stockmovements">
   <div class="table-responsive">

@if ($stockmovements->count())
<table id="stockmovements" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Date')}}</th>{{l('')}}
			<th>{{l('Type')}}</th>
			<th>{{l('Warehouse')}}</th>
            <th>{{l('Reference')}}</th>
            <th>{{l('Product')}}</th>
            <th>{{l('Quantity')}}</th>
			<th>{{l('Price')}}</th>
			<th>{{l('Document')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>

	@foreach ($stockmovements as $stockmovement)
		<tr>
			<td>{{ $stockmovement->id }}</td>
			<td>{{ abi_date_short( $stockmovement->date ) }}</td>
            <td>
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $movement_typeList[$stockmovement->movement_type_id] }}">
                        {{ $stockmovement->movement_type_id }}
                    </button>
                 </a>
                </td>

			<td>{{ $stockmovement->warehouse->alias }}</td>
            <td>    @if ( $stockmovement->combination_id > 0 )
                        {{ $stockmovement->combination->reference }}
                    @else
                        {{ $stockmovement->product->reference }}
                    @endif
            </td>
			<td>{{ $stockmovement->product->name }}
                    @if ( $stockmovement->combination_id > 0 )
                        <br />{{ $stockmovement->combination->name() }}
                    @endif
            </td>
            <td>{{ $stockmovement->as_quantity( 'quantity' ) }}</td>
			<td>{{ $stockmovement->as_price( 'price' ) }}</td>
			<td>{{ $stockmovement->document_reference }}</td>
            <td class="text-center">
                @if ($stockmovement->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $stockmovement->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>

            <td class="text-right">
                @if (  is_null($stockmovement->deleted_at))
                <!-- a class="btn btn-sm btn-success" href="{{ URL::to('stockmovements/' . $stockmovement->id) }}" title=" Ver "><i class="fa fa-eye"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id . '/edit') }}" title=" Modificar "><i class="fa fa-pencil"></i></a -->
                <!-- a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('stockmovements/' . $stockmovement->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Stock Movements') }} ::  ({{$stockmovement->id}}) {{ $stockmovement->date }} ?" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->
                @else
                <a class="btn btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
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
</div>

@stop

@include('layouts/modal_delete')