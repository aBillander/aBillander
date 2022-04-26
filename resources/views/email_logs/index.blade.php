@extends('layouts.master')

@section('title') {{ l('Email Logs') }} @parent @endsection


@section('content')



<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('emaillogs/create') }}" class=" hide btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

    </div>
    <h2>
        {{ l('Email Logs') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'emaillogs.index', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_from_form', l('Date from')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_to_form', l('Date to')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('email_from', l('Email from')) !!}
        {!! Form::text('email_from', null, array('id' => 'email_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('email_to', l('Email to')) !!}
        {!! Form::text('email_to', null, array('id' => 'email_to', 'class' => 'form-control')) !!}
    </div>

<div class=" hide form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class=" hide form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class=" hide form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('warehouse_id', l('Warehouse')) !!}
    {!! Form::select('warehouse_id', array('0' => l('All', [], 'layouts')) + ($warehouseList = []), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('emaillogs.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

<div class="row">

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('subject', l('Subject')) !!}
        {!! Form::text('subject', null, array('id' => 'subject', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('body', l('Body')) !!}
        {!! Form::text('body', null, array('id' => 'body', 'class' => 'form-control')) !!}
    </div>


</div>



                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>



<div id="div_emaillogs">
   <div class="table-responsive">

@if ($emaillogs->count())
<table id="emaillogs" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Date')}}</th>
			<th>{{l('To')}}</th>{{l('')}}
			<th>{{l('Subject')}}</th>
			<th>{{l('Body')}}</th>
      <th>{{l('From')}}</th>
      <th>{{l('Attachments')}}</th>
      <th>{{l('User')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>

	@foreach ($emaillogs as $emaillog)
		<tr>
			<td>{{ $emaillog->id }}</td>
			<td>{{ abi_date_short( $emaillog->created_at ) }}</td>
      <td>{{ $emaillog->to }}<br />
@if($emaillog->cc)
          
          CC: {{ $emaillog->cc }}<br />
@endif
@if($emaillog->bcc)
          BCC: {{ $emaillog->bcc }}<br />
@endif
      </td>
      <td>{{ $emaillog->subject }}</td>
      <td>
                @if ($emaillog->body)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="auto" 
                            data-title="{!! $emaillog->subject !!}" data-content="{{ $emaillog->body }}" html="true" data-container="body">
                        <i class="fa fa-paperclip" title="{{l('View', [], 'layouts')}}"></i>
                    </button>
                 </a>
                @endif
      </td>
      <td>{{ $emaillog->from }}</td>
      <td>
                @if ($emaillog->attachments)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="auto" 
                            data-title="{!! $emaillog->subject !!}" data-content="{{ $emaillog->attachments }}" html="true" data-container="body">
                        <i class="fa fa-file-o" title="{{l('View', [], 'layouts')}}"></i>
                    </button>
                 </a>
                @endif
      </td>
      <td>[{{ $emaillog->userable_id}}] {{ optional($emaillog->userable)->getFullName() }}</td>

            <td class="text-right">
                @if (  is_null($emaillog->deleted_at))
                <!-- a class="btn btn-sm btn-success" href="{{ URL::to('emaillogs/' . $emaillog->id) }}" title=" Ver "><i class="fa fa-eye"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('emaillogs/' . $emaillog->id . '/edit') }}" title=" Modificar "><i class="fa fa-pencil"></i></a -->
                <!-- a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('emaillogs/' . $emaillog->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Email Logs') }} ::  ({{$emaillog->id}}) {{ $emaillog->date }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->
                @else
                <a class=" hide btn btn-warning" href="{{ URL::to('emaillogs/' . $emaillog->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class=" hide btn btn-danger" href="{{ URL::to('emaillogs/' . $emaillog->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach

	</tbody>
</table>
{!! $emaillogs->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $emaillogs->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection

{{-- @include('layouts/modal_delete') --}}

@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
</script>

@endsection

@section('styles')    @parent

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}


.popover{
   max-width: 552px !important;    
}


</style>

@endsection