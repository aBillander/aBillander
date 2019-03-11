@extends('absrc.layouts.master')

@section('title') {{ l('Documents') }} @parent @stop


@section('content')


<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a href="{{ route('customerorders.create.withcustomer', $customer->id) }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

    </div>
    <h2>
        <a class="btn btn-sm {{ $model_class::getBadge('a_class') }}" href="{{ URL::to($model_path.'') }}" title="{{l('Documents')}}"><i class="fa {{ $model_class::getBadge('i_class') }}"></i></a> <span style="color: #cccccc;">/</span> 
                  {{ l('Documents') }} <span class="lead well well-sm">

                  <a href="{{ URL::to('absrc/customers/' . $customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{{ $customer->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$customer->identification}}<br />
                                  {{ $customer->address->address1 }} {{ $customer->address->address2 }}<br />
                                  {{ $customer->address->postcode }} {{ $customer->address->city }}, {{ $customer->address->state->name }}<br />
                                  {{ $customer->address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
                 @if($customer->sales_equalization)
                  <span id="sales_equalization_badge" class="badge" title="{{l('Equalization Tax')}}"> RE </span>
                 @endif
                 </span>
                   &nbsp; 
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $customer->currency->name }}">{{ $customer->currency->iso_code }}</span>
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
    </h2>        
</div>


{!! Form::open( ['method' => 'POST', 'id' => 'form-select-documents'] ) !!}

{!! Form::hidden('customer_id', $customer->id, array('id' => 'customer_id')) !!}

<div id="div_documents">

<div class="row">

   <div class="col-lg-9 col-md-9">
   <div class="table-responsive">

@if ($documents->count())
<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
            <th class="text-left">{{ l('ID', 'layouts') }}</th>
            <th class="text-center"></th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Warehouse') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Deliver to') }}
              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Address is displayed if it is different from Customer Main Address') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a></th>
            <th class="text-left">{{ l('Shipping Method') }}</th>
            <!-- th class="text-left">{{ l('Created via') }}</th -->
            <th class="text-right"">{{ l('Total') }}</th>
            <th class="text-center">{{ l('Notes', 'layouts') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="document_lines">
        @foreach ($documents as $document)
        <tr>
            <td class="text-center warning">{!! Form::checkbox('document_group[]', $document->id, false, ['class' => 'case xcheckbox']) !!}</td>
            <td>
                <a xclass="btn btn-sm btn-warning" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}" target="_blank">
                    @if ($document->document_id>0)
                      {{ $document->document_reference }}
                    @else
                      <span class="btn btn-xs btn-grey xlabel xlabel-default">{{ l('Draft') }}</span>
                    @endif
                </a>
            </td>
            <td class="text-center">

@if ( $document->status == 'closed' )
                <a class="btn btn-xs alert-danger" href="#" title="{{l('Document closed', 'layouts')}}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-lock"></i>&nbsp;</a>
@endif

@if ($document->onhold>0)
                <a class="btn btn-xs btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
@else
                <a class="btn btn-xs alert-info" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
@endif

@if ( $document->edocument_sent_at )
                <a class="btn btn-xs alert-success" href="#" title="{{l('Email sent:')}} {{ abi_date_short($document->document_date) }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-envelope-o"></i>&nbsp;</a>
@endif
                
            </td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>{{ optional($document->warehouse)->alias }}</td>
            <td>{{ abi_date_short($document->delivery_date) }}</td>
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
            <td>{{ optional($document->shippingmethod)->name }}
            </td>
            <!-- td>{{ $document->created_via }}
            </td -->
            <td class="text-right">{{ $document->as_money_amount('total_tax_incl') }}</td>
            <td class="text-center">@if ($document->all_notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($document->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-right">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customeror ders/' . $document->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{ { URL::to('customer orders/' . $document->id) } }" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->

@php

$f = $document->stock_flag;

switch ( $f ) {
    case 1:
        # code...
        $class = 'btn-success';
        break;

    case 0:
        # code...
        $class = 'btn-warning';
        break;
    
    case -1:
    default:
        # code...
        $class = 'btn-danger';
        break;
}

@endphp

                <a class="btn btn-sm {{ $class }} show-stock-availability" data-id="{{$document->id}}" title="{{l('Stock Availability')}}"><i class="fa fa-th"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

{{--
@if ( \App\Configuration::isTrue('DEVELOPER_MODE') && 0)

                <a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id . '/duplicate') }}" title="{{l('Copy', 'layouts')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-info" href="{{ URL::to($model_path.'/' . $document->id . '/invoice/pdf') }}" title="{{l('PDF Invoice', [], 'layouts')}}"><i class="fa fa-money"></i></a>

                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('customer orders/' . $document->id . '/shippingslip') }}" title="{{l('Document', [], 'layouts')}}"><i class="fa fa-file-pdf-otruck"></i></a -->

                <a class="btn btn-sm btn-lightblue xbtn-info" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-truck"></i></a>
@endif

@if ($document->document_id>0)
                <a class="btn btn-sm btn-lightblue"    href="{{ URL::to($model_path.'/' . $document->id . '/email') }}" title="{{l('Send by eMail', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
@endif

                <!-- a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a -->
                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to($model_path.'/' . $document->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Documents') }} :: ({{$document->id}}) {{ $document->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
--}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $documents->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $documents->total() ], 'layouts')}} </span></li></ul>
<ul class="pagination" style="float:right;"><li xclass="active" style="float:right;"><span style="color:#333333;border-color:#ffffff"> <div class="input-group"><span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Per page', 'layouts')}}</span><input id="items_per_page" name="items_per_page" class="form-control input-sm items_per_page" style="width: 50px !important;" type="text" value="{{ $items_per_page }}" onclick="this.select()">
    <span class="input-group-btn">
      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getCustomerOrders(); return false;"><i class="fa fa-refresh"></i></button>
    </span>
  </div></span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>

@if ($documents->count())

   <div class="col-lg-3 col-md-3">

<div class="with-nav-tabs panel panel-info" id="panel_forms"> 

   <div class="panel-heading">

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default_s" data-toggle="tab" style="font-size: 16px;">{{ l('Group Orders') }}</a></li>
                            <li><a href="#tab2default_s" data-toggle="tab" style="font-size: 16px;">{{ l('Create Shipping Slip') }}</a></li>
                        </ul>

   </div>

  <div class="tab-content">
      <div class="tab-pane fade in active" id="tab1default_s">
                
                @include($view_path.'.index_form_aggregate')

      </div>
      <div class="tab-pane fade" id="tab2default_s">
                
                @include($view_path.'.index_form_group')

      </div>
  </div>


</div>


    </div><!-- div class="col-lg-3 col-md-3" ENDS -->



{{--
   <div class="col-lg-3 col-md-3">

            <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">{{ l('Group Orders') }}</h3>
              </div>

      @include($view_path.'.index_form_aggregate')
                  
              </div>

            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">{{ l('Create Shipping Slip') }}</h3>
              </div>

      @include($view_path.'.index_form_group')
                  
              </div>

    </div>
--}}

@endif

   </div>


</div><!-- div class="row" ENDS -->

</div><!-- div id="div_documents" ENDS -->


{!! Form::close() !!}


@endsection

@include('layouts/modal_delete')

@include($view_path.'._modal_document_availability')


{{-- *************************************** --}}



@if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') )

@if ($model_path=='customerorders')


        @include($view_path.'._chunck_manufacturing')


@endif

@endif


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




    $(document).on('keydown','.items_per_page', function(e){
  
      if (e.keyCode == 13) {
       // console.log("put function call here");
       e.preventDefault();
       getCustomerOrders();
       return false;
      }

    });

    function getCustomerOrders( items_per_page = 0 ){
      
      window.location = "{{ route('customer.shippingslipable.orders', $customer->id) }}"+"?items_per_page="+$("#items_per_page").val();

      // 
      // $('#form-select-documents-per-page').submit();

    }


</script>


<script type="text/javascript">

$(document).ready(function() {

   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });


    $('#document_date_form').val('{{ abi_date_form_short( 'now' ) }}');
    $('#order_document_date_form').val('{{ abi_date_form_short( 'now' ) }}');

//    $('#sequence_id').val('{{ $customer->getInvoiceSequenceId() }}');
//    $('#template_id').val('{{ $customer->getInvoiceTemplateId() }}');

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
    $( "#document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#order_document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
 /* 
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
*/  
</script>

@endsection




@section('styles') @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>


{{-- Nav-tabs --}}

<link href="{{ asset('assets/theme/css/nav-tabs.css') }}" rel="stylesheet" type="text/css"/>

@endsection
