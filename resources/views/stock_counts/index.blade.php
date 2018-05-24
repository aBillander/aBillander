@extends('layouts.master')

@section('title') {{ l('Stock Counts') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('stockcounts/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Stock Counts') }}
    </h2>        
</div>

<div id="div_stockcounts">
   <div class="table-responsive">

@if ($stockcounts->count())
<table id="stockcounts" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{ l('Count #') }}</th>
			<th>{{ l('Date') }}</th>
            <th>{{l('Warehouse')}}</th>
            <th class="text-center">{{l('Initial Inventory?')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($stockcounts as $stockcount)
		<tr>
			<td>{{ $stockcount->document_reference }}</td>
			<td>{{ abi_date_short($stockcount->document_date) }}</td>
            <td>{{ $stockcount->warehouse->alias }}</td>
            <td class="text-center">@if ($stockcount->initial_inventory) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
			
            <td class="text-center">
                @if ($stockcount->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $stockcount->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>

			<td class="text-right">

                <a class="btn btn-sm btn-blue" href="{{ URL::to('stockcounts/' . $stockcount->id . '/stockcountlines') }}" title="{{l('Stock Count Lines')}}"><i class="fa fa-folder-open-o"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('stockcounts/' . $stockcount->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('stockcounts/' . $stockcount->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Stock Counts') }} :: ({{$stockcount->document_reference}}) {{ abi_date_short($stockcount->document_date) }} ?" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

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
