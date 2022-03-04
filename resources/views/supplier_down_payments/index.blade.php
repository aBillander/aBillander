@extends('layouts.master')

@section('title') {{ l('Down Payment to Suppliers') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ URL::to('supplierdownpayments/create') }}" class="btn btn-sm btn-success" 
            title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <!-- a href="{{ route('cheques.export', Request::all()) }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a -->


        <button type="button" class="btn btn-sm btn-behance" 
                data-toggle="modal" data-target="#supplierdownpaymentsHelp"
                title="{{l('Help', [], 'layouts')}}"><i class="fa fa-life-saver"></i> {{l('Help', [], 'layouts')}}</button>

    </div>
    <h2>
        {{ l('Down Payment to Suppliers') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'supplier.downpayments.index', 'method' => 'GET', 'id' => 'process')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('due_date_from_form', l('Due Date').' '.l('From', 'layouts')) !!}
        {!! Form::text('due_date_from_form', null, array('id' => 'due_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('due_date_to_form', l('Due Date').' '.l('To', 'layouts')) !!}
        {!! Form::text('due_date_to_form', null, array('id' => 'due_date_to_form', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('status', l('Status', 'layouts')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('autosupplier_name', l('Supplier')) !!}
    {!! Form::text('autosupplier_name', null, array('class' => 'form-control', 'id' => 'autosupplier_name')) !!}

    {!! Form::hidden('supplier_id', null, array('id' => 'supplier_id')) !!}
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
{!! link_to_route('supplier.downpayments.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>





<div id="div_downpayments">

@if ($downpayments->count())

   <div class="table-responsive">

<table id="downpayments" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Due Date') }}</th>
            <th>{{l('Document')}}</th>
            <th>{{l('Supplier')}}</th>
            <th>{{l('Bank')}}</th>
            <th>{{l('Amount')}}</th>
            <th>{{l('Currency')}}</th>
            <th class="text-center">{{l('Status', [], 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($downpayments as $downpayment)
		<tr>
            <td>{{ $downpayment->id }}</td>
            <td>{{ abi_date_short($downpayment->due_date) ?: '-' }}</td>
            <td>
  @if($downpayment->supplierorder)
              <a class="" href="{{ URL::to('supplierorders/' . $downpayment->supplier_order_id . '/edit') }}" 
                title="{{ l('Go to', 'layouts') }}" target="_new">
                  {{ $downpayment->supplierorder->document_reference ?: l('Draft', 'layouts') }}
              </a>
  @else
              -
  @endif
            </td>
            <td><a class="" href="{{ URL::to('suppliers/' . $downpayment->supplier->id . '/edit') }}" 
                title="{{ l('Go to', 'layouts') }}" target="_new">
                  {{ $downpayment->supplier->name_regular }}
              </a>
            </td>
            <td xclass="text-center">{{ optional($downpayment->bank)->name }}</td>

            <td>{{ $downpayment->amount > 0.0 ? $downpayment->as_money_amount('amount') : '-' }}</td>
            <td xclass="text-center">{{ $downpayment->currency->name }}</td>
            <td class="text-center button-pad">
              @if     ( $downpayment->status == 'pending' )
                <span class="label label-info">
              @elseif ( $downpayment->status == 'deposited' )
                <span class="label label-danger">
              @elseif ( $downpayment->status == 'applied' )
                <span class="label label-success">
              @elseif ( $downpayment->status == 'bounced' )
                <span class="label alert-danger">
              @else
                <span class="label">
              @endif
              {{ $downpayment->status_name }}</span>

              @if ( $downpayment->status == 'paid' )

                    <a href="{{ route('supplier.downpayment.bounce', [$downpayment->id]) }}" class="btn btn-xs btn-danger" 
                    title="{{l('Bounce Cheque')}}" xstyle="margin-left: 22px;"><i class="fa fa-mail-reply-all"></i></a>

              @endif

            </td>

              <td class="text-center">
                  @if ($downpayment->notes)
                   <a href="javascript:void(0);">
                      <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                              data-content="{{ $downpayment->notes }}">
                          <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                      </button>
                   </a>
                  @endif
              </td>

			<td class="text-right button-pad">
                @if (  is_null($downpayment->deleted_at))
{{--
                <a class="btn btn-sm btn-blue" href="{{ URL::to('supplierdownpayments/' . $downpayment->id . '/downpaymentlines') }}" title="{{l('Show Down Payment to Supplier Lines')}}"><i class="fa fa-folder-open-o"></i></a>
--}}
                <a class="btn btn-sm btn-warning" href="{{ URL::to('supplierdownpayments/' . $downpayment->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

      @if ($downpayment->status != 'applied')

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('supplierdownpayments/' . $downpayment->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Down Payment to Suppliers') }} :: ({{$downpayment->id}}) {{ $downpayment->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
      @endif
                @else
                <a class="btn btn-warning" href="{{ URL::to('supplierdownpayments/' . $downpayment->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('supplierdownpayments/' . $downpayment->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $downpayments->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $downpayments->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_downpayments" ENDS -->

@include('layouts/back_to_top_button')

@endsection

@include('layouts/modal_delete')

@include('supplier_down_payments/_modal_help')





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

        $("#autosupplier_name").autocomplete({
            source : "{{ route('home.searchsupplier') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                supplier_id = value.item.id;

                $("#autosupplier_name").val(value.item.name_regular);
                $("#supplier_id").val(value.item.id);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
                $("#supplier_id").val('');

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
