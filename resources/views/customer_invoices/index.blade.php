@extends('layouts.master')

@section('title') {{ l('Documents') }} @parent @stop


@section('content')


<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ URL::to($model_path.'/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

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

                {!! Form::model(Request::all(), array('route' => $model_path.'.index', 'method' => 'GET', 'id' => 'process')) !!}

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

<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('status', l('Status')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('payment_status', l('Payment Status')) !!}
    {!! Form::select('payment_status', array('' => l('All', [], 'layouts')) + $payment_statusList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('payment_method_id', l('Payment Method')) !!}
    {!! Form::select('payment_method_id', array('' => l('All', [], 'layouts')) + $payment_methodList, null, array('class' => 'form-control')) !!}
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
                              <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" xdata-container="body" 
                                        data-content="{ { l('With or without Taxes') } }">
                                    <i class="fa fa-question-circle abi-help"></i>
                              </a -->
    {!! Form::text('price_amount', null, array('class' => 'form-control', 'id' => 'price_amount')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route($model_path.'.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('document_reference', l('Document Number')) !!}
        {!! Form::text('document_reference', null, array('class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('id_from', l('From ID', 'layouts')) !!}
        {!! Form::text('id_from', null, array('id' => 'id_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('id_to', l('To ID', 'layouts')) !!}
        {!! Form::text('id_to', null, array('id' => 'id_to', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {{-- Poor man offset --}}
    </div>

@if( count($salesrepList) > 0 )
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('sales_rep_id', l('Sales Representative')) !!}
        {!! Form::select('sales_rep_id', ['' => l('All', [], 'layouts')] + $salesrepList, null, array('class' => 'form-control', 'id' => 'sales_rep_id')) !!}
    </div>
@endif

</div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>




{!! Form::open( ['method' => 'POST', 'id' => 'form-select-documents', 'target' => '_blank'] ) !!}


<div id="div_documents">

@if ($documents->count())

   <div class="table-responsive">

<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
            <th class="text-left">{{-- l('ID', 'layouts') --}}

<a class="btn btn-xs btn-blue" href="javascript:void(0);" title="{{l('Print selected Documents', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-select-documents').attr('action', '{{ route( 'customerinvoices.bulk.pdf' )}}');$('#form-select-documents').submit();return false;" target="_blank"><i class="fa fa-print"></i> &nbsp;{{l('Print', 'layouts')}}</a>

            </th>
            <th class="text-center"></th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Deliver to') }}</th>
            <th class="text-left">{{ l('Created via') }}</th>
            <th class="text-right">{{ l('Total') }}</th>
            <th class="text-right"> </th>
            <th class="text-center">{{ l('Next Due Date') }}</th>
            <th class="text-center">{{ l('Notes', 'layouts') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="document_lines">
        @foreach ($documents as $document)
        <tr>
            <td class="text-center warning">{!! Form::checkbox('document_group[]', $document->id, false, ['class' => 'case xcheckbox']) !!}</td>
            <td>{{ $document->id }} / 
                @if ($document->document_id>0)
                {{ $document->document_reference }}
                @else
                <a class="btn btn-xs btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
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
@else
    @if ($document->onhold>0)
                    <a class="btn btn-xs btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
    @else
                    <a class="btn btn-xs alert-info" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
    @endif
@endif

@if ( $document->edocument_sent_at )
                <a class="btn btn-xs alert-success" href="#" title="{{l('Email sent:')}} {{ abi_date_short($document->document_date) }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-envelope-o"></i>&nbsp;</a>
@endif
                
            </td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>{{ abi_date_short($document->delivery_date) }}</td>
            <td><a class="" href="{{ URL::to('customers/' .$document->customer->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ $document->customer->name_regular }}
            	</a>
            </td>
            <td>
                @if ( $document->hasShippingAddress() )



                {{ $document->shippingaddress->alias }} 
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $document->shippingaddress->firstname }} {{ $document->shippingaddress->lastname }}<br />{{ $document->shippingaddress->address1 }}<br />{{ $document->shippingaddress->city }} - {{ $document->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $document->shippingaddress->phone }}</a>" data-original-title="" title="">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      

                @endif
            </td>
            <td>{{ $document->created_via }}
            </td>
            <td class="text-right">{{ $document->as_money_amount('total_tax_incl') }}
@if ( $document->status == 'closed' )
    @if ( $document->payment_status == 'pending' )
                    <p class="text-danger"><strong>{{ $document->as_money_amount('total_tax_incl') }}</strong></p>
    @endif
    @if ( $document->payment_status == 'halfpaid' )
                    <p class="text-danger"><strong>{{ $document->as_money_amount('open_balance') }}</strong></p>
    @endif
@endif
            </td>
            <td>
@if ( $document->status == 'closed' )
    @if ( $document->payment_status == 'pending' )
                    <a class="btn btn-xs alert-danger" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}#payments" target="_new" title="{{ $document->payment_status_name }}">&nbsp;<i class="fa fa-window-close"></i>&nbsp;</a>
    @endif
    @if ( $document->payment_status == 'halfpaid' )
                    <a class="btn btn-xs alert-warning" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}#payments" target="_new" title="{{ $document->payment_status_name }}">&nbsp;<i class="fa fa-star-half-o"></i>&nbsp;</a>
    @endif
    @if ( $document->payment_status == 'paid')
                    <a class="btn btn-xs alert-success" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}#payments" target="_new" title="{{ $document->payment_status_name }}">&nbsp;<i class="fa fa-star"></i>&nbsp;</a>
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
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customeror ders/' . $document->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{ { URL::to('customer orders/' . $document->id) } }" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->
@if ( 0 )

                <a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id . '/duplicate') }}" title="{{l('Copy', 'layouts')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-info" href="{{ URL::to($model_path.'/' . $document->id . '/invoice/pdf') }}" title="{{l('PDF Invoice', [], 'layouts')}}"><i class="fa fa-money"></i></a>

                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('customer orders/' . $document->id . '/shippingslip') }}" title="{{l('Document', [], 'layouts')}}"><i class="fa fa-file-pdf-otruck"></i></a -->

                <a class="btn btn-sm btn-lightblue xbtn-info" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-truck"></i></a>
@endif

@if ($document->document_id>0)
                <a class="btn btn-sm btn-lightblue"    href="{{ URL::to($model_path.'/' . $document->id . '/email') }}" title="{{l('Send by eMail', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
@endif

                <a class=" hide btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                
                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to($model_path.'/' . $document->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Documents') }} :: ({{$document->id}}) {{ $document->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
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


{!! Form::close() !!}


@include('layouts/back_to_top_button')

@endsection

@include('layouts/modal_delete')


{{-- *************************************** --}}



@if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') )

@if ($model_path=='customerorders')


        @include($view_path.'._chunck_manufacturing')


@endif

@endif



{{-- *************************************** --}}


@section('scripts') @parent 

<script type="text/javascript">

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
                .append( '<div>[' + (item.active > 0 ? (item.identification == null ? ' - ' : item.identification) : '<span class="alert-danger">{{ l('Not Active', 'layouts') }}</span>') + '] ' + item.name_regular + "</div>" )
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
