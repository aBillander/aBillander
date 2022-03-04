@extends('layouts.master')

@section('title') {{ l('Commission Settlements') }} @parent @endsection


@section('content')

<div class="page-header">
@if($salesrep)
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ route('commissionsettlements.create', ['sales_rep_id' => $salesrep->id]) }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        <a href="{{ route('commissionsettlements.index') }}">{{ l('Commission Settlements') }}</a> <span style="color: #cccccc;">/</span> 

        <a href="{{ route('salesreps.edit', [$salesrep->id]) }}" target="_new">{{$salesrep->name}} </a> <span class="btn btn-xs btn-grey" title="{{l('Commission Percent')}}">{{ $salesrep->as_percent( 'commission_percent' ) }}%</span>
    </h2>
@else
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('commissionsettlements/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Commission Settlements') }}
    </h2>
@endif
</div>

<div id="div_settlements">
   <div class="table-responsive">

@if ($settlements->count())
<table id="settlements" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Date') }}</th>
            <th>{{ l('Sales Representative') }}</th>
            <th>{{ l('Documents from') }}</th>
            <th>{{ l('Documents to') }}</th>
            <th>{{ l('Commissionable') }}</th>
            <th>{{ l('Settlement') }}</th>
          <th class="text-center">{{l('Status', [], 'layouts')}}</th>
          <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($settlements as $settlement)
		<tr>
			<td>{{ $settlement->id }}</td>
			<td title="{{ $settlement->name }}">{{ abi_date_short($settlement->document_date) }}</td>
            <td>{{ $settlement->salesrep->name }}</td>
            <td>{{ abi_date_short($settlement->date_from) }}</td>
            <td>{{ abi_date_short($settlement->date_to) }}</td>
            <td>{{ $settlement->as_money_amount('total_commissionable') }}</td>
            <td>{{ $settlement->as_money_amount('total_settlement') }}</td>

            <td class="text-center">
              @if     ( $settlement->status == 'pending' )
                <span class="label label-danger">
              @elseif ( $settlement->status == 'closed' )
                <span class="label label-success">
              @else
                <span>
              @endif
              {{ $settlement->status_name }}</span>
            </td>

      <td class="text-center">
          @if ($settlement->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $settlement->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif</td>

			<td class="text-right">
                @if (  is_null($settlement->deleted_at))

                <a class="btn btn-sm btn-blue" href="{{ URL::to('commissionsettlements/' . $settlement->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class=" hide btn btn-sm btn-warning" href="{{ URL::to('commissionsettlementss/' . $settlement->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('commissionsettlements/' . $settlement->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Commission Settlements') }} :: ({{$settlement->id}}) {{ $settlement->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('commissionsettlements/' . $settlement->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('commissionsettlements/' . $settlement->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
{!! $settlements->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $settlements->total() ], 'layouts')}} </span></li></ul>
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
