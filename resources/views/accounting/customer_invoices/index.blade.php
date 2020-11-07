@extends('layouts.master')

@section('title') {{ l('Documents') }} @parent @stop


@section('content')


<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

    </div>
    <h2>
        {{ l('Documents') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'accounting.customerinvoices.index', 'method' => 'GET', 'id' => 'process')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('date_from_form', l('From', 'layouts')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('date_to_form', l('To', 'layouts')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('status', l('Status')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('payment_status', l('Payment Status')) !!}
    {!! Form::select('payment_status', array('' => l('All', [], 'layouts')) + $payment_statusList, null, array('class' => 'form-control')) !!}
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
    {!! Form::label('price_amount', l('Total Amount')) !!}
                              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" xdata-container="body" 
                                        data-content="{{ l('With or without Taxes') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                              </a>
    {!! Form::text('price_amount', null, array('class' => 'form-control', 'id' => 'price_amount')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('accounting.customerinvoices.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

<div class="row">

      <div class="form-group col-lg-2 col-md-2 col-sm-2">
          {!! Form::label('autoinvoice_from', l('Invoice from')) !!}
          {!! Form::text('autoinvoice_from', null, array('class' => 'form-control', 'id' => 'autoinvoice_from')) !!}

          {!! Form::hidden('invoice_from_id', null, array('id' => 'invoice_from_id')) !!}
      </div>

      <div class="form-group col-lg-2 col-md-2 col-sm-2">
          {!! Form::label('autoinvoice_to', l('Invoice to')) !!}
          {!! Form::text('autoinvoice_to', null, array('class' => 'form-control', 'id' => 'autoinvoice_to')) !!}

          {!! Form::hidden('invoice_to_id', null, array('id' => 'invoice_to_id')) !!}
      </div>


      <div class="form-group col-lg-2 col-md-2 col-sm-2">
          {!! Form::label('items_per_page', l('Items per page', 'layouts')) !!}
          {!! Form::text('items_per_page', null, array('class' => 'form-control', 'id' => 'items_per_page')) !!}
      </div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>




{!! Form::open( ['method' => 'POST', 'id' => 'form-select-documents'] ) !!}

{{-- !! Form::hidden('customer_id', $customer->id, array('id' => 'customer_id')) !! --}}

<div id="div_documents">

   <div class="table-responsive">

@if ($documents->count())
<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
            <th class="text-left">{{ l('ID', 'layouts') }}

<a class="btn btn-xs btn-blue" href="javascript:void(0);" title="{{l('Print selected Documents', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-select-documents').attr('target', '_blank');$('#form-select-documents').attr('action', '{{ route( 'customerinvoices.bulk.pdf', ['event' => 'Posted'] )}}');$('#form-select-documents').submit();return false;"><i class="fa fa-print"></i> &nbsp;{{l('Print', 'layouts')}}</a>

{{--
<div class="btn-group">

<a class="btn btn-xs btn-blue" href="javascript:void(0);" title="{{l('Print selected Documents', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-select-documents').attr('target', '_blank');$('#form-select-documents').attr('action', '{{ route( 'customerinvoices.bulk.pdf', ['event' => 'Posted'] )}}');$('#form-select-documents').submit();return false;"><i class="fa fa-print"></i> &nbsp;{{l('Print', 'layouts')}}</a>

  <a href="#" class="btn btn-xs btn-blue dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
  <ul class="dropdown-menu">
    <li>

<a xclass="btn btn-xs btn-blue" href="javascript:void(0);" title="{{l('Print Range of selected Documents', [], 'layouts')}}" onclick = "this.disabled=true;$('#print_range').val('1');$('#form-select-documents').attr('target', '_blank');$('#form-select-documents').attr('action', '{{ route( 'customerinvoices.bulk.pdf', ['event' => 'Posted'] )}}');$('#form-select-documents').submit();return false;"><i class="fa fa-arrows-h"></i> &nbsp;{{l('Range', 'layouts')}}</a>

    </li>
  </ul>
</div>
            {{ Form::hidden('print_range', 0, array('id' => 'print_range')) }}
--}}

            </th>
            <th class="text-center"></th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Posted at') }}
                  <a href="javascript:void(0);" data-toggle="popover" data-placement="top" xdata-container="body" 
                            data-content="{{ l('La fecha en la que se imprimió la factura por primera vez') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                  </a></th>
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Created via') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th class="text-right""> </th>
            <th class="text-center">{{ l('Next Due Date') }}</th>
            <th class="text-center">{{ l('Notes', 'layouts') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="document_lines">
        @foreach ($documents as $document)
        <tr>
            <td class="text-center warning">{!! Form::checkbox('document_group[]', $document->id, false, ['class' => 'case xcheckbox']) !!}</td>
            <td title="{{ $document->id }}"> 
                @if ($document->document_id>0)
                {{ $document->document_reference }}
                @else
                <a class="btn btn-xs btn-grey" href="javascript::void(0);"><i class="fa fa-hand-stop-o"></i>
                <span xclass="label label-default">{{ l('Draft') }}</span>
                </a>
                @endif
                @if ($document->all_notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($document->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-center button-pad">

@if ( $document->status == 'closed' )
                <a class="btn btn-xs alert-danger" href="#" title="{{l('Document closed', 'layouts')}}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-lock"></i>&nbsp;</a>
@endif
                
            </td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>{{ abi_date_short($document->posted_at) }}</td>
            <td><a class="" href="{{ route('accounting.customers.edit', $document->customer->id ) }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ $document->customer->name_regular }}
            	</a>
            </td>
            <td>{{ $document->created_via }}
            </td>
            <td class="text-right">{{ $document->as_money_amount('total_tax_incl') }}</td>
            <td>
@if ( $document->status == 'closed' )
@if ( $document->payment_status == 'pending' )
                <a class="btn btn-xs alert-danger" href="#" title="{{ $document->payment_status_name }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-window-close"></i>&nbsp;</a>
@endif
@if ( $document->payment_status == 'halfpaid' )
                <a class="btn btn-xs alert-warning" href="#" title="{{ $document->payment_status_name }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-star-half-o"></i>&nbsp;</a>
@endif
@if ( $document->payment_status == 'paid')
                <a class="btn btn-xs alert-success" href="#" title="{{ $document->payment_status_name }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-star"></i>&nbsp;</a>
@endif
@endif
            </td>
            <td class="text-center @if ( optional($document->nextPayment())->is_overdue ) danger @endif ">
                {{ abi_date_short($document->next_due_date) }}
            </td>
            <td class="text-center">@if ($document->all_notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($document->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-right button-pad">

@if ( $document->status == 'closed' )
                <a class="btn btn-sm btn-grey" href="{{ route('accounting.customerinvoices.pdf', $document->id) }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
@endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $documents->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $documents->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_documents" ENDS -->

@include('layouts/back_to_top_button')

@endsection

@include('layouts/modal_delete')


{{-- *************************************** --}}



{{-- *************************************** --}}


@section('scripts') @parent 

<script>

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery

$(function () {
    var $tblChkBox = $("#document_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});

$("#document_lines").on("change", function () {
    if (!$(this).prop("checked")) {
        $("#ckbCheckAll").prop("checked", false);
    }
});

// check box selection ENDS -->



{{--
    $(document).on('keydown','.items_per_page', function(e){
  
      if (e.keyCode == 13) {
       // console.log("put function call here");
       e.preventDefault();
       getCustomerShippingSlips();
       return false;
      }

    });

    function getCustomerShippingSlips( items_per_page = 0 ){
      
      window.location = "{{ route('customer.shippingslips', $customer->id) }}"+"?items_per_page="+$("#items_per_page").val();

      // 
      // $('#form-select-documents-per-page').submit();

    }
--}}


</script>


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

        $("#autoinvoice_from").autocomplete({
            source : "{{ route('accounting.customerinvoices.searchinvoice') }}",
            minLength : 2,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                var str = '' + value.item.document_reference+' - ' + roundTo(value.item.total_tax_incl, 2);

                $("#autoinvoice_from").val(str);
                $('#invoice_from_id').val(value.item.id);

                // getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>' + item.document_reference+' - ' + roundTo(item.total_tax_incl, 2) + "</div>" )
                .appendTo( ul );
            };


        $("#autoinvoice_to").autocomplete({
            source : "{{ route('accounting.customerinvoices.searchinvoice') }}",
            minLength : 2,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                var str = '' + value.item.document_reference+' - ' + roundTo(value.item.total_tax_incl, 2);

                $("#autoinvoice_to").val(str);
                $('#invoice_to_id').val(value.item.id);

                // getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>' + item.document_reference+' - ' + roundTo(item.total_tax_incl, 2) + "</div>" )
                .appendTo( ul );
            };



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


    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });


   $('#process').submit(function(event) {

     if ( $("#autocustomer_name").val() == '' ) $('#customer_id').val('');

     if ( $("#autoinvoice_from").val() == '' ) $('#invoice_from_id').val('');
     if ( $("#autoinvoice_to"  ).val() == '' ) $('#invoice_to_id'  ).val('');

     return true;

   });



    // Round numbers
    // https://stackoverflow.com/questions/15762768/javascript-math-round-to-two-decimal-places
    function roundTo(n, digits) {
        var negative = false;
        if (digits === undefined) {
            digits = 0;
        }
            if( n < 0) {
            negative = true;
          n = n * -1;
        }
        var multiplicator = Math.pow(10, digits);
        n = parseFloat((n * multiplicator).toFixed(11));
        n = (Math.round(n) / multiplicator).toFixed(2);
        if( negative ) {    
            n = (n * -1).toFixed(2);
        }
        return n;
    }
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
