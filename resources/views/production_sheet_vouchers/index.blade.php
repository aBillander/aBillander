@extends('layouts.master')

@section('title') {{ l('Customer Vouchers') }} @parent @stop


@section('content')


<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn xbtn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>
{{--
        <button name="b_pay_multiple" id="b_pay_multiple" class="btn btn-sm btn-blue" htype="button" title="{{l('Pay multiple Vouchers at once')}}"><i class="fa fa-money"></i>
           &nbsp; {{l('Pay multiple')}}
        </button>
--}}
{{--
        <a href="{{ route('customervouchers.export', ['customer_id' => $customer->id, 'autocustomer_name' => $customer->name_regular] + Request::all()) }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>
--}}



                <div class="btn-group" style="margin-left: 32px; ">
                    <a href="#" class="btn xbtn-sm btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Go to', 'layouts')}}" style="background-color: #31b0d5;
border-color: #269abc;"><i class="fa fa-mail-forward"></i> &nbsp;{{l('Go to', 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right">
                      <li><a href="{{ route('productionsheet.orders', [$productionSheet->id]) }}"><i class="fa fa-shopping-bag"></i> {{ l('Customer Orders') }}</a></li>
                      <li><a href="{{ route('productionsheet.shippingslips', [$productionSheet->id]) }}"><i class="fa fa-truck"></i> {{ l('Shipping Slips') }}</a></li>
                      <li><a href="{{ route('productionsheet.invoices', [$productionSheet->id]) }}"><i class="fa fa-money"></i> {{ l('Customer Invoices') }}</a></li>

                      <li><a href="{{ route('productionsheet.vouchers', [$productionSheet->id]) }}"><i class="fa fa-credit-card text-info"></i> {{ l('Customer Vouchers') }}</a></li>

                      <li class="divider"></li>
                      <!-- li class="divider"></li -->
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>

        <a href="{{ route('productionsheets.show', [$productionSheet->id]) }}" class="btn xbtn-sm btn-default" title="{{ l('Back to Production Sheet') }}"><i class="fa fa-mail-reply"></i> {{ l('Back', 'layouts') }}</a>

    </div>
    <h2>
        <a class="btn btn-sm btn-blue" href="{{ URL::to('customervouchers') }}" title="{{l('Customer Vouchers')}}"><i class="fa fa-credit-card"></i></a> 

        <span style="color: #cccccc;">/</span> 

                  <span class="lead well well-sm">

                    <a href="{{ URL::to('productionsheets') }}">{{ l('Production Sheet') }}</a> <span style="color: #cccccc;">::</span> {{ abi_date_form_short($productionSheet->due_date) }}

                 <a href="{{ route('productionsheets.show', [$productionSheet->id]) }}" class="btn btn-xs btn-default" title="{{ l('Back to Production Sheet') }}"><i class="fa fa-mail-reply"></i></a>

                 </span>

         <span style="color: #cccccc;">/</span> 


    </h2>        
    <h2>     
@php
    $documents_total_count = $payments_total_count;
    $documents_total = $payments->total();

    if ($documents_total < $documents_total_count)
    {
        $btn_class = 'grey';

    } else 
    {
        $btn_class = 'success';

    }
@endphp
                  {{ l('Customer Vouchers') }} 
                   &nbsp; <span class="btn btn-sm btn-{{ $btn_class }}" title="{{ l('Showing :a of :b in total', ['a' => $documents_total, 'b' => $documents_total_count]) }}">{{ $documents_total }} / {{ $documents_total_count }}</span> 
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
    </h2>
</div>

{{-- --}}
<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => ['productionsheet.vouchers', $productionSheet->id], 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('payment_type_id') ? 'has-error' : '' }}">
        {!! Form::label('payment_type_id', l('Payment Type')) !!}
                        <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                  data-content="{{ l('If you do not select anything, every Voucher will go with its Payment Type.') }}">
                              <i class="fa fa-question-circle abi-help"></i>
                        </a -->
        {!! Form::select('payment_type_id', array('' => l('All', [], 'layouts')) + $payment_typeList, null, array('class' => 'form-control')) !!}
        {!! $errors->first('payment_type_id', '<span class="help-block">:message</span>') !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('status', l('Status')) !!}
        {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
    </div>


{{-- 
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_from_form', l('Date from', 'layouts')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_to_form', l('Date to', 'layouts')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>


    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('amount', l('Amount')) !!}
        {!! Form::text('amount', null, array('id' => 'amount', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('status', l('Status')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>


<div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-auto_direct_debit">
     {!! Form::label('auto_direct_debit', l('Auto Direct Debit'), ['class' => 'control-label']) !!}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                        data-content="{{ l('Include in automatic payment remittances') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                   </a>
     <div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('auto_direct_debit', '1', false, ['id' => 'auto_direct_debit_on']) !!}
           {!! l('Yes', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('auto_direct_debit', '0', false, ['id' => 'auto_direct_debit_off']) !!}
           {!! l('No', [], 'layouts') !!}
         </label>
       </div>
       <div class="radio-inline">
         <label>
           {!! Form::radio('auto_direct_debit', '-1', true, ['id' => 'auto_direct_debit_all']) !!}
           {!! l('All', [], 'layouts') !!}
         </label>
       </div>
     </div>
</div>
--}}

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

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('productionsheet.vouchers', l('Reset', [], 'layouts'), [$productionSheet->id], array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>

{{-- --}}


{!! Form::open( ['method' => 'POST', 'id' => 'form-select-payments'] ) !!}

{!! Form::hidden('production_sheet_id', $productionSheet->id, array('id' => 'production_sheet_id')) !!}

<div id="div_vouchers">

<div class="row">

   <div class="col-lg-9 col-md-9">

        @include('production_sheet_vouchers.index_vouchers_list')

   </div>

@if ($payments->count())

   <div class="col-lg-3 col-md-3">

<div class="with-nav-tabs panel panel-info" id="panel_forms"> 

   <div class="panel-heading">

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default_s" data-toggle="tab" style="font-size: 16px;">{{ l('Pay multiple Vouchers at once') }}</a></li>
                            <!-- li><a href="#tab2default_s" data-toggle="tab" style="font-size: 16px;">{{ l('Create Invoices') }}</a></li -->
                        </ul>

   </div>

  <div class="tab-content">
      <div class="tab-pane fade in active" id="tab1default_s">
                
          @if ($payments->where('status', 'pending')->count())

                @include('production_sheet_vouchers.index_form_pay')
          
          @else
              <div class="panel-body">
              
                  <div class="alert alert-warning alert-block">
                      <i class="fa fa-warning"></i>
                      {{l('Todos los Recibos están pagados.')}}
                  </div>              
              </div>
          @endif

      </div>
      <div class="tab-pane fade" id="tab2default_s">
{{--
          @if ($documents->where('status', '!=', 'closed')->count())

                @include('production_sheet_orders.index_form_group')
          
          @else
              <div class="panel-body">
              
                  <div class="alert alert-warning alert-block">
                      <i class="fa fa-warning"></i>
                      {{l('Se han creado Albaranes para todos los Pedidos.')}}
                  </div>
              
              </div>

              <div class="panel-footer">
              
              </div>
          @endif
--}}
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


</div><!-- div class="row" ENDS -->

</div><!-- div id="div_vouchers" ENDS -->


@include('layouts/back_to_top_button')


{!! Form::close() !!}


@endsection
{{--
@include('layouts/modal_delete')

@include('production_sheet_orders._modal_document_availability')
--}}

{{-- *************************************** --} }



@if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') )

@if ($model_path=='customerorders')


        @include($view_path.'._chunck_manufacturing')


@endif

@endif


{ {-- *************************************** --}}


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
      
      window.location = "{{ route('customer.shippingslipable.orders', $productionSheet->id) }}"+"?items_per_page="+$("#items_per_page").val();

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
    $('#delivery_date_form').val('{{ abi_date_form_short( 'now' ) }}');
    $('#order_document_date_form').val('{{ abi_date_form_short( 'now' ) }}');

//    $('#status').val('confirmed');

//    $('#sequence_id').val('{ { $customer->getInvoiceSequenceId() }}');
//    $('#template_id').val('{ { $customer->getInvoiceTemplateId() }}');

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
    $( "#delivery_date_form" ).datepicker({
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
