@extends('absrc.layouts.master')

@section('title') {{ l('My Invoices') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

    </div>
    <h2>
        {{ l('Customer Invoices') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'absrc.invoices.index', 'method' => 'GET', 'id' => 'process')) !!}

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
{!! link_to_route('absrc.invoices.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>





<div id="div_customer_invoices">
   <div class="table-responsive">

@if ($customer_invoices->count())
<table id="customer_invoices" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('Invoice #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Payment Method') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th class="text-right">{{ l('Open Balance') }}</th>
            <th class="text-right">{{ l('Next Due Date') }}</th>
            <th class="text-center">{{ l('Notes', 'layouts') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customer_invoices as $invoice)
        <tr>
            <td>{{ $invoice->id }} / 
                @if ($invoice->document_id>0)
                {{ $invoice->document_reference }}
                @else
                <span class="label label-default" title="{{ l('Draft') }}">{{ l('Draft') }}</span>
                @endif</td>
            <td>{{ abi_date_short($invoice->document_date) }}</td>
            <td><a class="" href="{{ URL::to('absrc/customers/' .$invoice->customer->id . '/edit') }}" title="{{ l('Show Customer') }}">
            	{{ optional($invoice->customer)->name_regular }}
            	</a>
            </td>
            <td>{{ $invoice->paymentmethod->name }}
            	<!-- a class="btn btn-xs btn-success" href="{{ URL::to('customerinvoices/' . $invoice->id) }}" title="{{ l('Show Payments') }}"><i class="fa fa-eye"></i></a -->
        	</td>
            <td class="text-right">{{ $invoice->as_money_amount('total_tax_incl') }}</td>
            <td class="text-right">{{ $invoice->as_money_amount('open_balance') }}</td>
            <td  @if( $invoice->next_due_date AND ( $invoice->next_due_date < \Carbon\Carbon::now() ) ) class="danger" @endif>
                @if ($invoice->open_balance < pow( 10, -$invoice->currency->decimalPlaces ) AND 0 ) 
                @else
                    @if ($invoice->next_due_date) {{ abi_date_short($invoice->next_due_date) }} @endif
                @endif</td>
            <td class="text-center">@if ($invoice->all_notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($invoice->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-right">

                <a class='btn btn-sm btn-lightblue show-shippingslips' href="#" data-target='#myModalShowShippingSlips' data-id="{{ $invoice->secure_key }}" data-toggle="modal" onClick="return false;" title="{{l('Show Shipping Slips')}}"><i class="fa fa-truck"></i></a>

                <a class='btn btn-sm btn-success show-payments hidden' href="#" data-target='#myModalShowPayments' data-id="{{ $invoice->secure_key }}" data-toggle="modal" onClick="return false;" title="{{l('Show Payments')}}"><i class="fa fa-calendar"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ route('absrc.invoice.pdf',  ['invoiceKey' => $invoice->secure_key]) }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a> 

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $customer_invoices->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customer_invoices->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection

@include('absrc.invoices._modal_shippingslips')

{{--
@include('absrc.invoices._modal_payments')
--}}



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
            source : "{{ route('absrc.ajax.customerLookup') }}",
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

     return true;

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

