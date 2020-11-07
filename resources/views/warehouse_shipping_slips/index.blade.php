@extends('layouts.master')

@section('title') {{ l('Documents') }} @parent @stop


@section('content')


<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

{{--
        <div class="btn-group xopen">
          <a href="{{ route('warehouseshippingslips.index') }}" class="btn alert-success btn-sm" title="{{l('Filter Records', [], 'layouts')}}"><i class="fa fa-money"></i> &nbsp;{{l('All', [], 'layouts')}}</a>

          <a href="#" class="btn alert-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></a>

          <ul class="dropdown-menu">
            <li><a href="{{ route('warehouseshippingslips.index', 'invoiced_not') }}"><i class="fa fa-exclamation-triangle text-danger"></i> &nbsp; {{l('Not Invoiced')}}</a>
            </li>

            <li><a href="{{ route('warehouseshippingslips.index', 'invoiced') }}"><i class="fa fa-money text-muted"></i> &nbsp; {{l('Invoiced')}}</a>
            </li>

            <li class="divider"></li>
          </ul>
        </div>
--}}

        <a href="{{ URL::to('warehouseshippingslips/create') }}" class="btn btn-sm btn-success" 
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

                {!! Form::model(Request::all(), array('route' => 'warehouseshippingslips.index', 'method' => 'GET', 'id' => 'process')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_from_form', l('Date from', 'layouts')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_to_form', l('Date to', 'layouts')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('status', l('Status')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('shipment_status', l('Shipment Status')) !!}
    {!! Form::select('shipment_status', array('' => l('All', [], 'layouts')) + $shipment_statusList, null, array('class' => 'form-control')) !!}
</div>


{{--
<div class=" form-group col-lg-2 col-md-2 col-sm-2" id="div-is_invoiced">
     {!! Form::label('is_invoiced', l('Invoiced'), ['class' => 'control-label']) !!}
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('is_invoiced', '1', false, ['id' => 'is_invoiced_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('is_invoiced', '0', false, ['id' => 'is_invoiced_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('is_invoiced', '-1', true, ['id' => 'is_invoiced_all']) !!}
           {!! l('All', [], 'layouts') !!}
         </label>
       </div>
     </div>
</div>

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

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('carrier_id', l('Carrier')) !!}
    {!! Form::select('carrier_id', array('' => l('All', [], 'layouts')) + $carrierList, null, array('class' => 'form-control')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('warehouseshippingslips.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>





{!! Form::open( ['method' => 'POST', 'id' => 'form-select-documents', 'target' => '_blank'] ) !!}

{{-- !! Form::hidden('customer_id', $customer->id, array('id' => 'customer_id')) !! --}}

<div id="div_documents">

   <div class="table-responsive">

@if ($documents->count())
<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <!-- th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th -->
            <th class="text-left">{{ l('ID', 'layouts') }}
            </th>
            <th class="text-center"> </th>
            <th class="text-left">{{ l('Warehouse') }}</th>
            <th class="text-left">{{ l('Warehouse Counterpart') }}</th>
            <th class="text-left">{{ l('Document Date') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Sent Date') }}
              <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Address is displayed if it is different from Customer Main Address') }}">
                    <i class="fa fa-question-circle abi-help"></i -->
              </th>
            <th class="text-left">{{ l('Shipping Method') }}</th>
            <th class="text-left">{{ l('Carrier') }}</th>
            <th class="text-center">{{ l('Notes', 'layouts') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="document_lines">
        @foreach ($documents as $document)
        <tr>
            <!-- td class="text-center warning">{!! Form::checkbox('document_group[]', $document->id, false, ['class' => 'case xcheckbox']) !!}</td -->
            <td>{{ $document->id }} / 
                @if ($document->document_id>0)
                {{ $document->document_reference }}
                @else
                <a class="btn btn-xs btn-grey" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                <span xclass="label label-default">{{ l('Draft', 'layouts') }}</span>
                </a>
                @endif</td>
            <td class="text-center">


    @if ( $document->status == 'closed' )
                <a class="btn btn-xs alert-danger" href="#" title="{{l('Document closed', 'layouts')}}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-lock"></i>&nbsp;</a>
    @else
        @if ($document->onhold>0)
                    <a class="btn btn-xs btn-danger" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
        @else
                    <a class="btn btn-xs alert-info" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
        @endif
        @if ($document->document_id>0)
                <a class="btn btn-xs alert-success prevent-double-click" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/close') }}" title="{{l('Close Document', 'layouts')}}">&nbsp;<i class="fa fa-unlock"></i>&nbsp;{{-- l('Close', 'layouts') --}}</a>
        @endif

    @endif

@if ( $document->edocument_sent_at )
                <a class="btn btn-xs alert-success" href="#" title="{{l('Email sent:')}} {{ abi_date_short($document->edocument_sent_at) }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-envelope-o"></i>&nbsp;</a>
@endif
                
            </td>
            <td>[{{ $document->warehouse->alias }}] {{ $document->warehouse->name }}</td>
            <td>[{{ $document->warehousecounterpart->alias }}] {{ $document->warehousecounterpart->name }}</td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>{{ abi_date_short($document->delivery_date) }}</td>
            <td>{{ abi_date_short($document->delivery_date_real) }}</td>
            <td>{{ optional($document->shippingmethod)->name }}</td>
            <td>{{ optional($document->carrier)->name }}</td>
            <!-- td>
    @if ( $document->shipment_status == 'delivered' )
        @if ( \App\Configuration::isTrue('ENABLE_CRAZY_IVAN') )

                <div class="row btn-group">
                  <a href="#" class="btn btn-xs btn-blue" title="{{ l('Delivered at:') }} {{abi_date_short( $document->delivery_date_real )}}">&nbsp;<i class="fa fa-truck"></i>&nbsp;</a>
                  <a href="#" class="btn btn-xs btn-blue dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu" style="background-color: #ffffff; color: #333333;">
                    <li><a href="{{ URL::to('warehouseshippingslips/' . $document->id . '/undeliver') }}" class="hover-item" style="background-color: #ffffff; color: #333333;"><i class="fa fa-undo text-danger"></i> &nbsp; {{ l('Undo', 'layouts') }}</a></li>
                  </ul>
                </div>

        @else
                <a class="btn btn-xs btn-blue" href="#" title="{{ l('Delivered at:') }} {{abi_date_short( $document->delivery_date_real )}}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-truck"></i>&nbsp;</a>
        @endif
    @else
        @if ($document->status == 'closed')
                <a class="btn btn-xs alert-danger" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/deliver') }}" title="{{l('Set delivered')}}">&nbsp;<i class="fa fa-truck"></i>&nbsp;</a>
        @endif
    @endif
            </td>
            <td>{{ abi_date_short($document->delivery_date) }}</td>
            <td><a class="" href="{{ URL::to('customers/' . optional($document->customer)->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ optional($document->customer)->name_regular }}
            	</a>
            </td>
            <td>
                @if ( $document->shippingaddress )



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
            <td class="text-right">{{ $document->as_money_amount('total_tax_incl') }}</td -->
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
@if ( \App\Configuration::isTrue('DEVELOPER_MODE') && 0)

                <a class="btn btn-sm btn-success" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/duplicate') }}" title="{{l('Copy', 'layouts')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-info" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/invoice/pdf') }}" title="{{l('PDF Invoice', [], 'layouts')}}"><i class="fa fa-money"></i></a>

                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('customer orders/' . $document->id . '/shippingslip') }}" title="{{l('Document', [], 'layouts')}}"><i class="fa fa-file-pdf-otruck"></i></a -->

                <a class="btn btn-sm btn-lightblue xbtn-info" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-truck"></i></a>
@endif

@if ($document->document_id>0)
                <a class="btn btn-sm btn-lightblue"    href="{{ URL::to('warehouseshippingslips/' . $document->id . '/email') }}" title="{{l('Send by eMail', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank" onclick="open_in_new_tab_and_reload( this );return false;"><i class="fa fa-file-pdf-o"></i></a>
@endif

                <!-- a class="btn btn-sm btn-success" href="{{ URL::to('warehouseshippingslips/' . $document->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a -->

{{--
@if ($document->onhold>0)

@else

                @if ( $document->is_invoiceable && $document->status == 'closed' && !$document->invoiced_at)
                <a class="btn btn-sm btn-navy" href="{{ route('customershippingslip.invoice', [$document->id]) }}" title="{{l('Create Invoice')}}"><i class="fa fa-money"></i>
                </a>
                @endif
@endif
--}}

@if ( !$document->invoiced_at )
                <a class="btn btn-sm btn-warning" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
@endif

                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('warehouseshippingslips/' . $document->id ) }}" 
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

{{-- $documents->appends( collect(Request::all())
                            ->map(function($item) {
                                    // Take empty keys, otherwise skipped!
                                    return is_null($item) ? 1 : $item;
                            })->toArray() )->render() --}}
{!! $documents->appends( Request::all() )->render() !!}
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



    // https://stackoverflow.com/questions/1681679/disabling-links-to-stop-double-clicks-in-jquery

    $("a.prevent-double-click").one("click", function() {
        $(this).click(function () { return false; });
    });


   });



{{-- https://www.kodingmadesimple.com/2016/10/bootstrap-popover-form-example.html

If you double-initialize and your popover uses values that may change or custom content, etc., you will be in a world of hurt:
https://stackoverflow.com/questions/26562366/bootstrap-popover-is-not-working

^--  use:  rel="popover"




// popovers Initialization
$(function () {
$("#set_carrier_bulk").popover({
    title: '<strong>{{ l('Select Carrier') }}</strong>',
    container: 'body',
    placement: 'right',
    html: true, 
    content: function(){
          return $('#set_carrier_bulk_popover-form').html();
    }
    
});
});
 --}}


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


   // Open in new tab & reload

    function open_in_new_tab_and_reload(link)
    {
      // Open in new tab
      var href = link.href;
      window.open(href, '_blank');
      //focus to that window
      window.focus();
      //reload current page
      // Delay
      setTimeout(function() {
          // alert('Was called after 2 seconds');
          location.reload();
      }, 4000);
      
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
