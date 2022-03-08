@extends('absrc.layouts.master')

@section('title') {{ l('Commission Settlements') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
    </div>
    <h2>
        {{ l('Commission Settlements') }} <span style="color: #cccccc;">/</span> 

        {{$salesrep->name}} <span class="btn btn-xs btn-grey" title="{{l('Commission Percent')}}">{{ $salesrep->as_percent( 'commission_percent' ) }}%</span>
    </h2>
</div>

<div id="div_settlements">
   <div class="table-responsive">

@if ($settlements->count())
<table id="settlements" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Date') }}</th>
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
                @if ( 1 || is_null($settlement->deleted_at))

                <a class="btn btn-sm btn-blue" href="{{ URL::to('absrc/commissionsettlements/' . $settlement->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-folder-open-o"></i></a>
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
