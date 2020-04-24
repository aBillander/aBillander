@extends('layouts.master')

@section('title') {{ l('WooCommerce Products') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a class="btn btn-sm alert-success" style="margin-right: 76px" href="{{ URL::route('wcategories.index') }}" title="{{l('Categories', [], 'layouts')}}"><i class="fa fa-list"></i> {{l('Categories')}} [WooC]</a> 

        <a class=" hide btn btn-sm btn-grey" style="margin-right: 21px" href="javascript:void(0);" title="{{l('Import', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-import').attr('action', '{{ route( 'worders.import.orders' )}}');$('#form-import').submit();return false;"><i class="fa fa-download"></i> {{l('Import', 'layouts')}}</a>


        <button  name="b_search_filter" id="b_search_filter" class=" hide btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

    <a class="btn btn-sm btn-success" style="margin-right: 152px" href="{{ URL::route('wooconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}}"><i class="fa fa-cog"></i> {{l('Configuration', [], 'layouts')}} [WooC]</a> 

{{--
    <div class="btn-group" style="margin-right: 152px">
        <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Configuration', [], 'layouts')}}"><i class="fa fa-cog"></i> {{l('Configuration', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="{{ URL::route('wooconnect.configuration') }}">{{l('Shop Configuration')}}</a></li>
          <li><a href="{{ URL::route('wooconnect.configuration') }}">{{l('WooConnect Configuration')}}</a></li>
          <li><a href="{{ URL::route('wooconnect.configuration.taxes') }}">{{l('Taxes Dictionary')}}</a></li>
          <li><a href="{{ URL::route('wooconnect.configuration.paymentgateways') }}">{{l('Payment Gateways Dictionary')}}</a></li>
          <li><a href="{{ URL::route('wooconnect.configuration.shippingmethods') }}">{{l('Shipping Methods Dictionary')}}</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
    </div>
--}}

    </div>

    <h2>
        <a href="#">{{ config('woocommerce.store_url') }}</a> <span style="color: #cccccc;">/</span> {{ l('Products') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'worders.index', 'method' => 'GET')) !!}

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

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('worders.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>





<div id="div_orders">

@if ($products->count())

{!! Form::open( ['route' => ['productionsheet.addorders', '0'], 'method' => 'POST', 'id' => 'form-import'] ) !!}
{{-- !! csrf_field() !! --}}

   <div class="table-responsive">

<table id="orders" class="table table-hover">
	<thead>
		<tr>
      <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
      <th class="text-left">{{l('SKU')}}</th>
      <th colspan="2"><span style="font-weight: normal !important">{{l('Product Name')}}</span><br />{{l('Slug')}}</th>
      <th>{{l('Category')}}</th>
      <th>{{l('Type')}}</th>
      <th>{{l('Status')}}</th>
      <th>{{l('Price')}} ({{ \aBillander\WooConnect\WooConnector::getWooSetting( 'woocommerce_currency' ) }})</th>
      <th>{{l('Sale Price')}}</th>
      <th>{{l('Regular Price')}}</th>
      <th>{{l('Tax')}}</th>
      <th>{{l('Weight')}}</th>

      <th>{{l('Description')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody id="order_lines">
	@foreach ($products as $product)

		<tr>
			@if ( $product["imported_at"] ?? 0 )
      <td> </td>
      @else
      <td class="text-center warning">{!! Form::checkbox('worders[]', $product['id'], false, ['class' => 'case checkbox']) !!}</td>
      @endif
      <td title="{{ $product['id'] }}">{{ $product["sku"] }}
              
        @if ( ($pid = $abi_product_ids[$product["sku"]]) > 0 )
                        <a class="btn btn-xs btn-warning" href="{{ route('products.edit', $pid) }}" title="{{l('Go to local Product')}}" target="_blank"><i class="fa fa-external-link"></i></a>
        @endif
      </td>
      <td><img width="32px" src="{{ optional($product['images'])[0]['src'] }}" style="border: 1px solid #dddddd;"></td>
      <td>{{ $product["name"] }}<br /><strong>{{ $product["slug"] }}</strong></td>
      <td>{{ $product["categories"][0]["name"] }}</td>
      <td>{{ $product["type"] }}</td>
      <td>{{ $product["status"] }}</td>
      <td>{{ $product["price"] }}</td>
      <td>{{ $product["sale_price"] }}</td>
      <td>{{ $product["regular_price"] }}</td>
@php

$tax_id = \aBillander\WooConnect\WooOrder::getTaxId($product["tax_class"]);

$tax = \App\Tax::find( $tax_id );

@endphp
      <td>{{ optional($tax)->name ?: $product["tax_class"] }}</td>
      <td>{{ $product["weight"] }}</td>

      <td class="text-center" style="width:1px; white-space: nowrap;">
      @if ($product['description'])
       <a href="javascript:void(0);">
          <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                  data-content="{{ $product['description'] }}">
              <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
          </button>
       </a>
      @endif</td>

			<td class="text-right button-pad" xstyle="width:1px; white-space: nowrap;">

                <a class="btn btn-sm btn-blue" href="{{ URL::route('wproducts.fetch', [$product["sku"] ?: $product["id"]] ) }}" title="{{l('Fetch', [], 'layouts')}}" target="_blank"><i class="fa fa-eyedropper"></i></a>

        @if ( !($pid = $abi_product_ids[$product["sku"]]) > 0 )
                <a class="btn btn-sm btn-grey" href="{{ URL::route('wproducts.import', [$product["id"]] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-download"></i></a>
        @endif

{{--
                <a class='open-AddBookDialog btn btn-sm btn-warning' href="{{ URL::route('worders.update', [$product["id"]] + $query ) }}" data-target='#myModalOrder' data-id="{{ $product["id"] }}" data-status="{{ $product["status"] }}" data-statusname="{{ \aBillander\WooConnect\WooConnector::getOrderStatusName( $product["status"] ) }}" data-toggle="modal" onClick="return false;" title="{{l('Update', [], 'layouts')}}"><i class="fa fa-pencil-square-o"></i></a>

                <a class="btn btn-sm btn-success" href="{{ URL::to('wooc/worders/' . $product["id"]) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>
      
      @if ( $product["imported_at"] ?? 0 )
                <a class="btn btn-sm btn-lightblue" href="{{ URL::route('customerorders.edit', [$product["abi_order_id"]] ) }}" title="aBillander :: {{l('Show', [], 'layouts')}}"><i class="fa fa-file-text-o"></i></a>
      @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('worders.import', [$product["id"]] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-download"></i></a>
      @endif

                <!-- a class="btn btn-sm btn-info" href="{{ URL::route('worders.invoice', [$product["id"]] ) }}" title="{{l('Invoice', [], 'layouts')}}"><i class="fa fa-file-text"></i></a -->

                <!-- a class='open-deleteDialog btn btn-danger' data-target='#myModal1' data-id="{{ $product["id"] }}" data-toggle='modal'>{{l('Delete', [], 'layouts')}}</a -->
--}}
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

   </div>

{{ $products->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->total() ], 'layouts')}} </span></li></ul>


<div name="search_filter" id="search_filter">
<div class=" hide row" style="padding: 0 20px">

    <div class="col-md-2 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading" style="color: #ffffff; background-color: #772953; border-color: #772953;">
                <h3 class="panel-title">{{ l('Import Products') }}</h3>
            </div>
            <div class="panel-body">

<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6" style="padding-top: 22px">


                <a class="btn btn-grey" href="javascript:void(0);" title="{{l('Import', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-import').attr('action', '{{ route( 'worders.import.orders' )}}');$('#form-import').submit();return false;"><i class="fa fa-download"></i> {{l('Import', 'layouts')}}</a>

                <!-- https://stackoverflow.com/questions/6799533/how-to-submit-a-form-with-javascript-by-clicking-a-link -->

    </div>
</div>


            </div>
        </div>
    </div>





</div>
</div>


{!! Form::close() !!}

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div>

@endsection


{{-- *************************************** --}}


@section('modals')

@parent

<div class="modal fade" id="myModalOrder" role="dialog">

   <div class="modal-dialog">



       <!-- Modal content-->

       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal">&times;</button>

               <h4 class="modal-title">{{l('Change Order Status')}}</h4>

           </div>

           <div class="modal-body">

               <!-- p>Some text in the modal.</p -->


{!! Form::open(array('url' => '', 'method' => 'PATCH', 'id' => 'change_woo_order_status', 'name' => 'change_woo_order_status', 'class' => 'form')) !!}

                  
<div class="row">
		<div class="form-group col-lg-6 col-md-6 col-sm-6">
		                       <label for="bookId" xclass="col-sm-3 control-label text-right">{{l('Order #')}}</label>
		                       <input type="text" class="form-control" name="bookId" id="bookId" value="" onfocus="this.blur();">
		</div>

		<div class="form-group col-lg-6 col-md-6 col-sm-6">
		                       <label for="bookStatus" xclass="col-sm-3 control-label text-right">{{l('Status')}}</label>
		                       <input type="text" class="form-control" name="bookStatus" id="bookStatus" value="" onfocus="this.blur();">
		</div>
</div>

<div class="row">
  <div class="form-group col-lg-6 col-md-6 col-sm-6">
                         <label for="order_status">{{l('New Order Status')}}</label>

                         {!! Form::select('order_status', \aBillander\WooConnect\WooConnector::getOrderStatusList(), null, array('class' => 'form-control', 'id' => 'order_status')) !!}
  </div>

  <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-order_set_paid">
   {!! Form::label('order_set_paid', l('Mark as Paid'), ['class' => 'control-label']) !!}
   <div>
     <div class="radio-inline">
       <label>
         {!! Form::radio('order_set_paid', '1', false, ['id' => 'order_set_paid_on']) !!}
         {!! l('Yes', [], 'layouts') !!}
       </label>
     </div>
     <div class="radio-inline">
       <label>
         {!! Form::radio('order_set_paid', '0', true, ['id' => 'order_set_paid_off']) !!}
         {!! l('No', [], 'layouts') !!}
       </label>
     </div>
   </div>
  </div>

</div>


                   



                   <div class="modal-footer">

                       <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                       <button type="submit" class="btn btn-success" name="btn-update" onclick="this.disabled=true;this.form.submit();">
                       	<i class="fa fa-thumbs-up"></i>
                  		&nbsp; {{l('Update', [], 'layouts')}}</button>

                   </div>

{!! Form::close() !!}

           </div>

       </div>

   </div>

</div>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

<script>

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery

$(function () {
    var $tblChkBox = $("#order_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});

$("#order_lines").on("change", function () {
    if (!$(this).prop("checked")) {
        $("#ckbCheckAll").prop("checked", false);
    }
});

// check box selection ENDS -->


    $(document).ready(function () {
          // $(document).on("click", ".open-AddBookDialog", function() {
            $('.open-AddBookDialog').click(function (evnt) {

               var href = $(this).attr('href');
               var myBookId = $(this).attr('data-id');
               var myBookStatus = $(this).attr('data-status');
               var myBookStatusname = $(this).attr('data-statusname');

               $('#change_woo_order_status').attr('action', href);
               $(".modal-body #bookId").val(myBookId);
               $(".modal-body #bookStatus").val(myBookStatusname);
               $(".modal-body #order_status").val(myBookStatus);

               // https://blog.revillweb.com/jquery-disable-button-disabling-and-enabling-buttons-with-jquery-5e3ffe669ece
               // $('#btn-update').prop('disabled', false);

               $('#myModalOrder').modal({show: true});

               return false;

           });

          // Select first element
          $('#production_sheet_id option:first-child').attr("selected", "selected");
    });

</script>


<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#due_date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection




@section('styles') @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection