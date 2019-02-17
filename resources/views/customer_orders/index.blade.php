@extends('layouts.master')

@section('title') {{ l('Customer Orders') }} @parent @stop


@section('content')

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )

<div class="alert alert-block alert-info" style="display:none">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Info: </strong>
            {{ \App\Configuration::get('FSOL_CBDCFG') }} 
</div>

@if ( $anyClient > 0 )
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Aviso: </strong>
            Hay <b>{{$anyClient}}</b> ficheros en la Carpeta de descarga de <b>Clientes</b>. Debe importarlos a FactuSOL, o borrarlos. 

                <a style="color: #e95420; text-decoration: none;" class="btn btn-sm btn-grey" href="{{ route('fsxorders.deletecustomerfiles') }}" title="{{l('Eliminar Ficheros')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> Eliminar Ficheros</a>
</div>
@endif

@if ( $anyOrder > 0 )
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Aviso: </strong>
            Hay <b>{{$anyOrder}}</b> ficheros en la Carpeta de descarga de <b>Pedidos</b>. Debe importarlos a FactuSOL, o borrarlos. 

                <a style="color: #e95420; text-decoration: none;" class="btn btn-sm btn-grey" href="{{ route('fsxorders.deleteorderfiles') }}" title="{{l('Eliminar Ficheros')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> Eliminar Ficheros</a>
</div>
@endif
@endif

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a href="{{ URL::to('customerorders/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a href="{{ route('chart.customerorders.monthly') }}" class="btn btn-sm btn-warning" 
                title="{{l('Reports', [], 'layouts')}}"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}}</a>

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
        <a class="btn btn-sm btn-grey" xstyle="margin-right: 152px" href="{{ route('fsxconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}} {{l('Enlace FactuSOL', 'layouts')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> {{l('Configuration', [], 'layouts')}}</a> 
@endif

    </div>
    <h2>
        {{ l('Customer Orders') }}
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

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
            <th class="text-left">{{ l('Export to FS')}}</th>
@endif
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Deliver to') }}</th>
            <th class="text-left">{{ l('Created via') }}</th>
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
                <a class="btn btn-xs btn-grey" href="{{ URL::to('customerorders/' . $order->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-thumbs-o-up"></i>
                <span xclass="label label-default">{{ l('Draft') }}</span>
                </a>
                @endif</td>
            <td>{{ abi_date_short($order->document_date) }}</td>
            <td>{{ abi_date_short($order->delivery_date) }}</td>

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
              <td>{{ abi_date_short($order->export_date) }}
@endif

              </td>
            
            <td><a class="" href="{{ URL::to('customers/' .$order->customer->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ $order->customer->name_regular }}
            	</a>
            </td>
            <td>
                @if ( $order->hasShippingAddress() )



                {{ $order->shippingaddress->alias }} 
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $order->shippingaddress->firstname }} {{ $order->shippingaddress->lastname }}<br />{{ $order->shippingaddress->address1 }}<br />{{ $order->shippingaddress->city }} - {{ $order->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $order->shippingaddress->phone }}</a>" data-original-title="" title="">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      

                @endif
            </td>
            <td>{{ $order->created_via }}
            </td>
            <td class="text-right">{{ $order->as_money_amount('total_tax_incl') }}</td>
            <td class="text-center">@if ($order->all_notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($order->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-right button-pad">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customerorders/' . $order->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->
@if ( \App\Configuration::isTrue('DEVELOPER_MODE') )

                <a class="btn btn-sm btn-info" href="{{ URL::to('customerorders/' . $order->id . '/invoice/pdf') }}" title="{{l('PDF Invoice', [], 'layouts')}}"><i class="fa fa-money"></i></a>

                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('customerorders/' . $order->id . '/shippingslip') }}" title="{{l('Shipping Slip', [], 'layouts')}}"><i class="fa fa-file-pdf-otruck"></i></a -->

                <a class="btn btn-sm btn-lightblue xbtn-info" href="{{ URL::to('customerorders/' . $order->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-truck"></i></a>
@endif

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
                @if ($order->export_date)
                <a class="btn btn-sm btn-default" style="display:none;" href="javascript:void(0);" title="{{$order->export_date}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('fsxorders.export', [$order->id] ) }}" title="{{l('Exportar a FactuSOL')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @endif
@endif

                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id . '/duplicate') }}" title="{{l('Copy Order')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if( $order->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customerorders/' . $order->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customer Orders') }} :: ({{$order->id}}) {{ $order->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
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

@endsection

@include('layouts/modal_delete')


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
