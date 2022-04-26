@extends('layouts.master')

@section('title') {{ l('Delivery Sheets') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('deliverysheets/create') }}" class="btn btn-sm btn-success  hidden " 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>


        <button type="button" class="btn btn-sm btn-behance" 
                data-toggle="modal" data-target="#deliverysheetsHelp"
                title="{{l('Help', [], 'layouts')}}"><i class="fa fa-life-saver"></i> {{l('Help', [], 'layouts')}}</button>

    </div>
    <h2>
        {{ l('Delivery Sheets') }}
    </h2>        
</div>

<div id="div_deliverysheets">
   <div class="table-responsive">

@if ($deliverysheets->count())
<table id="deliverysheets" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Alias') }}</th>
            <th>{{l('Delivery Sheet name')}}</th>
            <th>{{l('Carrier')}}</th>
            <th>{{l('Driver name')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($deliverysheets as $deliverysheet)
		<tr>
            <td>{{ $deliverysheet->id }}</td>
            <td>{{ $deliverysheet->alias }}</td>
			      <td>{{ $deliverysheet->name }}</td>
            <td>{{ $deliverysheet->carrier->name }}</td>
            <td>{{ $deliverysheet->driver_name }}</td>

            <td class="text-center">@if ($deliverysheet->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

              <td class="text-center">
                  @if ($deliverysheet->notes)
                   <a href="javascript:void(0);">
                      <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                              data-content="{{ $deliverysheet->notes }}">
                          <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                      </button>
                   </a>
                  @endif
              </td>

			<td class="text-right">
                @if (  is_null($deliverysheet->deleted_at))
                <a class="btn btn-sm btn-blue" href="{{ URL::to('deliverysheets/' . $deliverysheet->id . '/deliverysheetlines') }}" title="{{l('Show Delivery Sheet Stops')}}"><i class="fa fa-folder-open-o"></i></a>
                <a class="btn btn-sm btn-warning" href="{{ URL::to('deliverysheets/' . $deliverysheet->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('deliverysheets/' . $deliverysheet->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Delivery Sheets') }} :: ({{$deliverysheet->id}}) {{ $deliverysheet->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('deliverysheets/' . $deliverysheet->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('deliverysheets/' . $deliverysheet->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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

@endsection

@include('layouts/modal_delete')

@include('delivery_sheets/_modal_help')

