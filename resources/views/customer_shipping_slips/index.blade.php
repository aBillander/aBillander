@extends('layouts.master')

@section('title') {{ l('Customer Shipping Slips') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a href="{{ URL::to('customershippingslips/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>


    </div>
    <h2>
        {{ l('Customer Shipping Slips') }}
    </h2>        
</div>

<div id="div_customer_shipping_slips">


   <div class="table-responsive">

@if ($customer_shipping_slips->count())
<table id="customer_shipping_slips" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
            <th class="text-left">{{ l('Shipping Slip #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Production Date')}}</th>
            <th class="text-left">{{ l('Export to FS')}}</th>
            <!-- th class="text-left">{{ l('Delivery Date') }}</th -->
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Payment Method') }}</th>
            <th class="text-left">{{ l('Created via') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="order_lines">
        @foreach ($customer_shipping_slips as $order)
        <tr>
            @if ( $order->production_sheet_id )
              <td> </td>
            @else
              <td class="text-center warning">{!! Form::checkbox('corders[]', $order->id, false, ['class' => 'case checkbox']) !!}</td>
            @endif
            <td>{{ $order->id }} / 
                @if ($order->document_id>0)
                {{ $order->document_reference }}
                @else
                <span class="label label-default" title="{{ l('Draft') }}">{{ l('Draft') }}</span>
                @endif</td>
            <td>{{ abi_date_short($order->document_date) }}</td>
            <!-- td>{{ abi_date_short($order->delivery_date) }}</td -->

              <td>
              
        @if ($order->production_sheet_id)
                        {{ abi_date_form_short($order->productionsheet->due_date) }} 
                        <a class="btn btn-xs btn-warning" href="{{ URL::to('productionsheets/' . $order->production_sheet_id) }}" title="{{l('Go to Production Sheet')}}"><i class="fa fa-external-link"></i></a>
        @endif
              </td>

              <td>
              
        @if ($order->export_date)
                        {{ abi_date_short($order->export_date) }}
        @endif
              </td>
            
            <td><a class="" href="{{ URL::to('customers/' .$order->customer->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ $order->customer->name_fiscal }}
            	</a>
            </td>
            <td>{{ $order->paymentmethod->name ?? '' }}
            </td>
            <td>{{ $order->created_via }}
            </td>
            <td class="text-right">{{ $order->as_money_amount('total_tax_incl') }}</td>
            <td class="text-right">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customerorders/' . $order->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->
@if (       config('app.url') =='http://abimfg.laextranatural.es' 
         || config('app.url') =='http://abimfg-test.laextranatural.es')

@else
                <a class="btn btn-sm btn-info" href="{{ URL::to('customershippingslips/' . $order->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-file-pdf-o"></i></a>
@endif

                <a class="btn btn-sm btn-success" href="{{ URL::to('customershippingslips/' . $order->id . '/duplicate') }}" title="{{l('Copy Shipping Slip')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('customershippingslips/' . $order->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if( $order->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customershippingslips/' . $order->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customer Shipping Slips') }} :: ({{$order->id}}) {{ $order->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $customer_shipping_slips->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customer_shipping_slips->total() ], 'layouts')}} </span></li></ul>


@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_customer_shipping_slips" ENDS -->

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
