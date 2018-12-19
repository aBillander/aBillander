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
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Date') }}</th>
            <th>{{ l('Name') }}</th>
            <th>{{l('Warehouse')}}</th>
            <th class="text-center">{{l('Initial Inventory?')}}</th>
            <th class="text-center">{{l('Processed?')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($stockcounts as $stockcount)
		<tr>
			<td>{{ $stockcount->id }}</td>
			<td>{{ abi_date_short($stockcount->document_date) }}</td>
            <td>{{ $stockcount->name }}</td>
            <td>[{{ $stockcount->warehouse->alias }}] {{ $stockcount->warehouse->name }}</td>
            <td class="text-center">@if ($stockcount->initial_inventory) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            
            <td class="text-center">@if ($stockcount->processed) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
			
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
                @if ( !$stockcount->processed || \App\Configuration::isTrue('DEVELOPER_MODE') )

                <a class="btn btn-sm btn-grey" href="{{ URL::route('stockcounts.import', [$stockcount->id] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-upload"></i></a>

                <a class="btn btn-sm btn-info update-warehouse-stock" data-html="false" data-toggle="modal" 
                        href="{{ URL::route('stockcount.warehouse.update', [$stockcount->id] ) }}" 
                        data-content="{{l('You are going to UPDATE the Stock of Products in Warehouse <i><u>:ws</u></i>. Are you sure?', ['ws' => $stockcount->warehouse->name])}}" 
                        data-wsname="{{ $stockcount->warehouse->name }}" 
                        data-title="{{ l('Stock Counts') }} :: ({{$stockcount->id}}) {{ $stockcount->name }}" 
                        onClick="return false;" title="{{l('Process Stock Count')}}"><i class="fa fa-superpowers"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::route('stockcounts.export', [$stockcount->id] ) }}" title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('stockcounts/' . $stockcount->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('stockcounts/' . $stockcount->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Stock Counts') }} :: ({{$stockcount->id}}) {{$stockcount->name}} [{{ abi_date_short($stockcount->document_date) }}]" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                
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


@include('stock_counts/_modal_update_warehouse_stock')

@include('layouts/modal_delete')
