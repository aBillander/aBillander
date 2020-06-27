@extends('layouts.master')

@section('title') {{ l('Customer Cheques') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ URL::to('cheques/create') }}" class="btn btn-sm btn-success" 
            title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>


        <button type="button" class="btn btn-sm btn-behance" 
                data-toggle="modal" data-target="#chequesHelp"
                title="{{l('Help', [], 'layouts')}}"><i class="fa fa-life-saver"></i> {{l('Help', [], 'layouts')}}</button>

    </div>
    <h2>
        {{ l('Customer Cheques') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'cheques.index', 'method' => 'GET', 'id' => 'process')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('date_of_issue_from_form', l('Date of Issue').' '.l('From', 'layouts')) !!}
        {!! Form::text('date_of_issue_from_form', null, array('id' => 'date_of_issue_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('date_of_issue_to_form', l('Date of Issue').' '.l('To', 'layouts')) !!}
        {!! Form::text('date_of_issue_to_form', null, array('id' => 'date_of_issue_to_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {{-- Spacer --}}
    </div>


    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('due_date_from_form', l('Due Date').' '.l('From', 'layouts')) !!}
        {!! Form::text('due_date_from_form', null, array('id' => 'due_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('due_date_to_form', l('Due Date').' '.l('To', 'layouts')) !!}
        {!! Form::text('due_date_to_form', null, array('id' => 'due_date_to_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {{-- Spacer --}}
    </div>

<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('status', l('Status', 'layouts')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('autocustomer_name', l('Customer')) !!}
    {!! Form::text('autocustomer_name', null, array('class' => 'form-control', 'id' => 'autocustomer_name')) !!}

    {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
</div>


{{--
<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('warehouse_id', l('Warehouse')) !!}
    {!! Form::select('warehouse_id', array('0' => l('All', [], 'layouts')) + $warehouseList, null, array('class' => 'form-control')) !!}
</div>
--}}


<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('price_amount', l('Amount')) !!}
                              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" xdata-container="body" 
                                        data-content="{{ l('With or without Taxes') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                              </a>
    {!! Form::text('price_amount', null, array('class' => 'form-control', 'id' => 'price_amount')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('cheques.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>





<div id="div_cheques">
   <div class="table-responsive">

@if ($cheques->count())
<table id="cheques" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Date of Issue') }}</th>
            <th>{{ l('Due Date') }}</th>
            <th>{{l('Document Number')}}</th>
            <th>{{l('Customer')}}</th>
            <th>{{l('Bank')}}</th>
            <th>{{l('Amount')}}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                    data-content="{{ l('This value is updated when a new Order is created, and cleared when a Template Line is created, updated or deleted.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
            <th>{{l('Currency')}}</th>
            <th class="text-center">{{l('Status', [], 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($cheques as $cheque)
		<tr>
            <td>{{ $cheque->id }}</td>
            <td>{{ abi_date_short($cheque->date_of_issue) ?: '-' }}</td>
            <td>{{ abi_date_short($cheque->due_date) ?: '-' }}</td>
			      <td>{{ $cheque->document_number }}</td>
            <td><a class="" href="{{ URL::to('customers/' . $cheque->customer->id . '/edit') }}" 
                title="{{ l('Go to', 'layouts') }}" target="_new">
                  {{ $cheque->customer->name_regular }}
              </a>
            </td>
            <td xclass="text-center">{{ optional($cheque->bank)->name }}</td>

            <td>{{ $cheque->amount > 0.0 ? $cheque->as_money_amount('amount') : '-' }}</td>
            <td xclass="text-center">{{ $cheque->currency->name }}</td>
            <td xclass="text-center">{{ $cheque->status_name }}</td>

              <td class="text-center">
                  @if ($cheque->notes)
                   <a href="javascript:void(0);">
                      <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                              data-content="{{ $cheque->notes }}">
                          <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                      </button>
                   </a>
                  @endif
              </td>

			<td class="text-right button-pad">
                @if (  is_null($cheque->deleted_at))
                <a class="btn btn-sm btn-blue" href="{{ URL::to('cheques/' . $cheque->id . '/chequelines') }}" title="{{l('Show Customer Cheque Lines')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('cheques/' . $cheque->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('cheques/' . $cheque->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Customer Cheques') }} :: ({{$cheque->id}}) {{{ $cheque->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('cheques/' . $cheque->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('cheques/' . $cheque->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $cheques->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $cheques->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_cheques" ENDS -->

@include('layouts/back_to_top_button')

@endsection

@include('layouts/modal_delete')

@include('cheques/_modal_help')





{{-- *************************************** --}}


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

{{-- Auto Complete --}}
{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(document).ready(function() {

        $("#autocustomer_name").autocomplete({
            source : "{{ route('home.searchcustomer') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customer_id = value.item.id;

                $("#autocustomer_name").val(value.item.name_regular);
                $("#customer_id").val(value.item.id);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_regular + "</div>" )
                .appendTo( ul );
            };


    $( "#date_of_issue_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    $( "#date_of_issue_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    $( "#due_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    $( "#due_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
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


/* Undeliver dropdown effect */
   .hover-item:hover {
      background-color: #d3d3d3 !important;
    }

</style>

@endsection
