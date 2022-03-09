@extends('layouts.master')

@section('title') {{ l('SEPA Direct Debits') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('sepasp/directdebits/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a href="{{ route('customervouchers.index') }}" class="btn xbtn-sm alert-info" 
            title="{{l('Go to', [], 'layouts')}}" style="margin-left: 22px;"><i class="fa fa-credit-card"></i> {{l('Customer Vouchers', 'customervouchers')}}</a>
    </div>
    <h2>
        {{ l('SEPA Direct Debits') }}
    </h2>        
</div>


<div id="div_sdds">
   <div class="table-responsive">

@if ($sdds->count())
<table id="sdds" class="table table-hover">
    <thead>
        <tr>
			<th>{{l('ID', [], 'layouts')}}</th>
      <th>{{ l('Date') }}</th>
      <th>{{ l('Validation Date') }}</th>
      <th>{{ l('Payment Date') }}</th>
			<th>{{ l('Amount') }} / {{ l('Vouchers') }}</th>
      <th class="text-center">{{l('Scheme')}}</th>
      <th>{{ l('Bank') }}</th>
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th class="text-right"> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($sdds as $sdd)
		<tr>
      <td>{{ $sdd->id }} / {{ $sdd->document_reference }}</td>
      <td>{{ abi_date_short($sdd->document_date) }}</td>
      <td>{{ abi_date_short($sdd->validation_date) }}</td>
      <td>{{ abi_date_short($sdd->payment_date) }}</td>
      <td>{{ $sdd->as_money_amount('total') }} / {{ $sdd->nbrItems() }}</td>
      <td class="text-center">{{ $sdd->scheme }}</td>
      <td>{{ optional($sdd->bankaccount)->bank_name }}</td>

            <td class="text-center">
              @if     ( $sdd->status == 'pending' )
                <span class="label label-danger">
              @elseif ( $sdd->status == 'confirmed' )
                <span class="label label-warning">
              @elseif ( $sdd->status == 'closed' )
                <span class="label label-success">
              @else
                <span>
              @endif
              {{ $sdd->status_name }}</span>
            </td>

      <td class="text-center">
          @if ($sdd->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $sdd->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif</td>

           <td class="text-right">

                <a class="btn btn-sm btn-blue" href="{{ URL::to('sepasp/directdebits/' . $sdd->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('sepasp/directdebits/' . $sdd->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

@if( $sdd->nbrItems() == 0 )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('sepasp/directdebits/' . $sdd->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('SEPA Direct Debits') }} :: ({{$sdd->id}}) {{ $sdd->document_reference }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
@endif

            </td>
		</tr>
	@endforeach
    </tbody>
</table>
{!! $sdds->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $sdds->total() ], 'layouts')}} </span></li></ul>
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
