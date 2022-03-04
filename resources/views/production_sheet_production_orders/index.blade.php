@extends('layouts.master')

@section('title') {{ l('Production Sheet - Production Orders') }} @parent @endsection


@section('content')<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn xbtn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

  <a class="btn xbtn-sm btn-success create-production-order" style="margin-left: 32px;" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

@php
    $productionSheet = $sheet;
@endphp

                <div class="btn-group" style="margin-left: 32px; ">
                    <a href="#" class="btn xbtn-sm btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Go to', 'layouts')}}" style="background-color: #31b0d5;
border-color: #269abc;"><i class="fa fa-mail-forward"></i> &nbsp;{{l('Go to', 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right">
                      <li><a href="{{ route('productionsheet.orders', [$productionSheet->id]) }}"><i class="fa fa-shopping-bag"></i> {{ l('Customer Orders') }}</a></li>
                      <li><a href="{{ route('productionsheet.shippingslips', [$productionSheet->id]) }}"><i class="fa fa-truck"></i> {{ l('Shipping Slips') }}</a></li>
                      <li><a href="{{ route('productionsheet.invoices', [$productionSheet->id]) }}"><i class="fa fa-money"></i> {{ l('Customer Invoices') }}</a></li>
                      <li class="divider"></li>
                      <!-- li class="divider"></li -->
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>

        <a href="{{ route('productionsheets.show', [$sheet->id]) }}" class="btn xbtn-sm btn-default" title="{{ l('Back to Production Sheet') }}"><i class="fa fa-mail-reply"></i> {{ l('Back', 'layouts') }}</a>

    </div>
    <h2>
        <a class="btn btn-sm alert-warning" href="{{ route('productionsheets.index') }}" title="{{l('Back to Production Sheets')}}"><i class="fa fa-cubes"></i></a> 

        <span style="color: #cccccc;">/</span> 

                  <span class="lead well well-sm">

                    <a href="{{ URL::to('productionsheets') }}">{{ l('Production Sheet') }}</a> <span style="color: #cccccc;">::</span> {{ abi_date_form_short($sheet->due_date) }}

                 <a href="{{ route('productionsheets.show', [$sheet->id]) }}" class="btn btn-xs btn-default" title="{{ l('Back to Production Sheet') }}"><i class="fa fa-mail-reply"></i></a>

                 </span>


         <span style="color: #cccccc;">/</span> 
                  {{ l('Production Orders') }} 
                   &nbsp; 
    </h2>        
</div>




<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => ['productionsheet.productionorders', $sheet->id], 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('work_center_id', l('Work Center')) !!}
    {!! Form::select('work_center_id', array('' => l('All', [], 'layouts')) + $work_centerList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('category_id', l('Category')) !!}
    {!! Form::select('category_id', array('' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('productionsheet.productionorders', l('Reset', [], 'layouts'), [$sheet->id], array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>




{!! Form::open( ['method' => 'POST', 'id' => 'form-select-documents'] ) !!}

{!! Form::hidden('production_sheet_id', $sheet->id, array('id' => 'production_sheet_id')) !!}

<div id="div_documents">

<div class="row">

   <div class="col-lg-9 col-md-9">

@if ($sheet->productionorders->count())
   <div class="table-responsive">

<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll', 'autocomplete' => "off"]) !!}</th>
            <th>{{l('ID', [], 'layouts')}}</th>
            <!-- th>{{l('Product ID')}}</th -->
            <th>{{l('Category')}}</th>
            <th>{{l('Product Name')}}</th>
            <th>{{l('Quantity')}}&nbsp;/<br />{{ l('Finished Quantity') }}</th>
            <th>{{l('Work Center')}}&nbsp;/<br />{{l('Warehouse')}}</th>
            <th>{{l('Provenience')}}</th>
            <th>{{l('Status', 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody id="document_lines">
        @foreach ($sheet->productionorders as $order)
  @php
    $product = $order->product;
  @endphp
  
    <tr>
            <td class="text-center warning">
@if ( $order->status == 'finished' )
@else
          @if ( 0 && $order->product->lot_tracking )
          @else
              {!! Form::checkbox('document_group[]', $order->id, false, ['class' => 'case xcheckbox']) !!}
          @endif
@endif

            </td>
      <td>{{ $order->id }}</td>
      <!-- td>{{ $order->product_id }}</td -->
      <td>
        <a href="{{ route('categories.subcategories.edit', [-1, $product->category_id]) }}" title="{{l('View Category')}}" target="_blank">{{ $product->category_id }}</a> {{ $product->category->name }}
      </td>
      <td>[<a href="{{ URL::to('products/' . $order->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $order->product_reference }}</a>] {{ $order->product_name }}
      </td>
      <td><span class="" title="{{ l('Planned Quantity') }}">{{ $product->as_quantityable($order->planned_quantity) }}</span>
@if( $order->finished_quantity > 0 )
        <br />
        <span class="text-success" title="{{ l('Finished Quantity') }}">{{ $product->as_quantityable($order->finished_quantity) }}</span> 
    @if( $order->product->lot_tracking )
        [
        <a href="{{ route( 'lot.stockmovements', optional(optional($order->lotitem)->lot)->id ) }}" title="{{ l('Lot Number') }}" target="_blank">{{ optional(optional($order->lotitem)->lot)->reference }}</a>
        ]
    @endif
@endif
        </td>
      <td>{{ $order->workcenter->name ?? '' }}
        <br />
        <span class="text-success" title="{{ $order->warehouse->alias_name ?? '' }}">{{ $order->warehouse->alias ?? '-' }}</span>
        </td>
      <td>{{ $order->created_via }}</td>
      <td class="xtext-center">
@if ( $order->status != 'finished' )
              <span class="label label-success" style="opacity: 0.75;">{{ $order->status_name }}</span>
@else
              <span class="label label-info" style="opacity: 0.75;">{{ $order->status_name }}</span><br />
              <span class="text-success" title="{{ l('Finish Date') }}"><xstrong>{{ abi_date_short($order->finish_date) }}</xstrong></span>
@endif
      </td>
      <td class="text-center">
          @if ($order->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $order->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif</td>

           <td class="text-right" style="width:1px; white-space: nowrap;">

@if ( $order->status != 'finished' )
                <a class="btn btn-sm 

          @if ( $order->product->lot_tracking )
                  btn-grey
          @else
                  btn-info
          @endif 


                finish-production-order  hide " href="{{ route('productionsheet.productionorders.finish') }}" title="{{l('Finish', [], 'layouts')}} {{ $order->product->lot_tracking ? ' :: con Control de Lote' : '' }}" data-oid="{{ $order->id }}" data-oproduct="{{ $order->product_id }}" data-oreference="{{ $order->product_reference }}" data-oname="{{ $order->product_name }}" data-oquantity="{{ $order->planned_quantity }}" 
                data-olot_reference=
          @if ( $order->product->lot_tracking )
                  "{{ \App\Lot::generate( \Carbon\Carbon::now(), $order->product, $order->product->expiry_time ) }}"
          @else
                  ""
          @endif 
                data-oworkcenter="{{ $order->work_center_id }}" data-ocategory="{{ $order->schedule_sort_order }}" data-onotes="{{ $order->notes }}" data-olottracking="{{ $order->product->lot_tracking }}" data-oexpirytime="{{ $order->product->expiry_time }}" data-oexpirydate="{{ $order->product->expiry_time }}" data-owarehouse="{{ $order->warehouse_id > 0 ? $order->warehouse_id : \App\Configuration::getInt('DEF_WAREHOUSE') }}" onClick="return false;">
          @if ( $order->product->lot_tracking )
                  <i class="fa fa-window-restore"></i>
          @else
                  <i class="fa fa-cubes"></i>
          @endif
                </a>

@endif

                <a class="btn btn-sm btn-blue show-production-order-products" title="{{l('Show', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-folder-open-o"></i></a>

@if ( $order->status != 'finished' )
{{--
                <a class="btn btn-sm btn-warning edit-production-order" href="{{ URL::to('productionorders/' . $order->id . '/productionsheetedit') }}" title="{{l('Edit', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oproduct="{{ $order->product_id }}" data-oreference="{{ $order->product_reference }}" data-oname="{{ $order->product_name }}" data-oquantity="{{ $order->planned_quantity }}" data-oworkcenter="{{ $order->work_center_id }}" data-ocategory="{{ $order->schedule_sort_order }}" data-onotes="{{ $order->notes }}" data-owarehouse="{{ $order->warehouse_id > 0 ? $order->warehouse_id : \App\Configuration::getInt('DEF_WAREHOUSE') }}" onClick="return false;"><i class="fa fa-pencil"></i></a>
--}}

                       
                <a class="btn btn-sm btn-warning " href="{{ URL::to('productionorders/' . $order->id . '/edit') }}"  title="{{l('Edit', [], 'layouts')}}" target="_productionorder"><i class="fa fa-pencil"></i></a>


                <a class="btn btn-sm btn-danger delete-production-order" href="{{ URL::to('productionorders/' . $order->id . '/productionsheetdelete') }}" title="{{l('Delete', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-trash-o"></i></a>
@endif

            </td>
    </tr>
  @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->


<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $sheet->productionorders->count() ], 'layouts')}} </span></li></ul>


{{--
{{ $documents->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $documents->total() ], 'layouts')}} </span></li></ul>
<!-- ul class="pagination" style="float:right;"><li xclass="active" style="float:right;"><span style="color:#333333;border-color:#ffffff"> <div class="input-group"><span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Per page', 'layouts')}}</span><input id="items_per_page" name="items_per_page" class="form-control input-sm items_per_page" style="width: 50px !important;" type="text" value="{{ $items_per_page }}" onclick="this.select()">
    <span class="input-group-btn">
      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getCustomerOrders(); return false;"><i class="fa fa-refresh"></i></button>
    </span>
  </div></span></li></ul -->
--}}
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>


   <div class="col-lg-3 col-md-3">

<div class="with-nav-tabs panel panel-info" id="panel_forms"> 

   <div class="panel-heading">

                        <ul class="nav nav-tabs">
                            <!-- li class="active"><a href="#tab1default_s" data-toggle="tab" style="font-size: 16px;">{{ l('Group Orders') }}</a></li -->
                            <li><a href="#tab2default_s" data-toggle="tab" style="font-size: 16px;">{{ l('Finish Production Orders') }}</a></li>
                        </ul>

   </div>

  <div class="tab-content">
      <div class="tab-pane fade" id="tab1default_s">
                
                @ include('production_sheet_orders.index_form_aggregate')

      </div>
      <div class="tab-pane fade in active" id="tab2default_s">
                
          @if ($sheet->productionorders->where('status', '<>', 'finished')->count())

                @include('production_sheet_production_orders.index_form_actions')
          
          @else
              <div class="panel-body">
              
                  <div class="alert alert-warning alert-block">
                      <i class="fa fa-warning"></i>
                      {{l('Todas las Ordenes de Fabricación están terminadas.')}}
                  </div>
              
              </div>

              <div class="panel-footer">
              
              </div>
          @endif

      </div>
  </div>


</div>


    </div><!-- div class="col-lg-3 col-md-3" ENDS -->


   </div>


</div><!-- div class="row" ENDS -->

</div><!-- div id="div_documents" ENDS -->


{!! Form::close() !!}


@include('production_sheet_production_orders._modal_production_order_finish')

@include('layouts/back_to_top_button')


{{-- What is this? => --}}

<hr style="margin:72px;border-top: 5px solid #eee;" />

@include('production_sheet_production_orders._block_legacy_stuff')

{{-- ^^- Whithout this, modals won't work!?? --}}

@endsection


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
      
      window.location = "{{ route('customer.shippingslipable.orders', $sheet->id) }}"+"?items_per_page="+$("#items_per_page").val();

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

    $('#status').val('confirmed');

//    $('#sequence_id').val('{ { $customer->getInvoiceSequenceId() }}');
//    $('#template_id').val('{ { $customer->getInvoiceTemplateId() }}');

    $('#orders_finish_date_form').val('{{ abi_date_short( \Carbon\Carbon::now() ) }}');
    $('#orders_warehouse_id').val('{{ \App\Configuration::getInt('DEF_WAREHOUSE') }}');

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


  $(function() {
    $( "#finish_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}",

        onSelect: function(dateText) {
          // alert("Selected date: " + dateText + ", Current Selected Value= " + this.value);

          getFinalProductLotNumber();          

          // If needed: $(this).change();
        }
    }).on("change", function() {

            console.log("Got change event from field");

        });
  });


  $(function() {
    $( "#orders_finish_date_form" ).datepicker({
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
