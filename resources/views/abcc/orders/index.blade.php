@extends('abcc.layouts.master')

@section('title') {{ l('My Orders') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
{{--
        <a href="{{ URL::to('customerorders/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a href="{{ route('chart.customerorders.monthly') }}" class="btn btn-sm btn-warning" 
                title="{{l('Reports', [], 'layouts')}}"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}}</a>
--}}
    </div>
    <h2>
        {{ l('My Orders') }}
    </h2>        
</div>

<div id="div_customer_orders">

   <div class="table-responsive">

@if ($customer_orders->count())
<table id="customer_orders" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('Order #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Deliver to') }}</th>
            <th class="text-right">{{ l('Items') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th class="text-center">{{ l('Notes') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="order_lines">
        @foreach ($customer_orders as $order)
        <tr>
            <td>{{ $order->id }} / 
                @if ($order->document_id>0)
                    {{ $order->document_reference }}
                @else
                    <span class="label label-default" title="{{ l('Draft', 'layouts') }}">{{ l('Draft') }}</span>
                @endif
                @if ( $order->notes_from_customer && mb_stripos( $order->notes_from_customer, 'quotation' ) !== false )
                    <br /><span class="label label-success" title="{{ l('Quotation') }}">{{ l('Quotation', 'layouts') }}</span>
                @endif
                </td>
            <td>{{ abi_date_short($order->document_date) }}</td>
            <td>{{ abi_date_short($order->delivery_date_real ?: $order->delivery_date) }}</td>
            <td>
                @if ( $order->hasShippingAddress() || 1 && 0)



                {{ optional($order->shippingaddress)->alias }} 
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $order->shippingaddress->firstname }} {{ $order->shippingaddress->lastname }}<br />{{ $order->shippingaddress->address1 }}<br />{{ $order->shippingaddress->city }} - {{ $order->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $order->shippingaddress->phone }}</a>" data-original-title="" title="">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      

                @endif
            </td>
            <td class="text-right">{{ $order->lines_count }}</td>
            <td class="text-right">{{ $order->as_money_amount('total_tax_excl') }}</td>
            <td class="text-center">@if ($order->notes_from_customer)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($order->notes_from_customer) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-right">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customerorders/' . $order->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->

                <!-- a class="btn btn-sm btn-lightblue" href="{ { URL::to('customerorders/' . $order->id . '/shippingslip') }}" title="{{l('Shipping Slip', [], 'layouts')}}"><i class="fa fa-truck"></i></a -->

                <a class="btn btn-sm btn-grey" href="{{ route('abcc.order.pdf', [$order->id]) }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ route('abcc.order.duplicate', [$order->id]) }}" title="{{l('Copy Order to Cart')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-success" href="{{ route('abcc.orders.show', [$order->id]) }}" title="{{l('View', [], 'layouts')}}"><i class="fa fa-eye"></i></a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $customer_orders->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customer_orders->total() ], 'layouts')}} </span></li></ul>


@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_customer_orders" ENDS -->

{{-- abi_r(Auth::user()) --}}

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

          // Select first element
          $('#production_sheet_id option:first-child').attr("selected", "selected");
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
  
</script>

@endsection




@section('styles') @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection
