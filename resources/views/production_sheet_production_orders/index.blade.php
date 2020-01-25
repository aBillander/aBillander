@extends('layouts.master')

@section('title') {{ l('Production Sheets - Show') }} @parent @stop


@section('content')<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <!--a href="{{ route('productionsheet.orders', [$sheet->id]) }}" class="btn btn-success" style="margin-left: 32px; margin-right: 32px; "><i class="fa fa-shopping-bag"></i> {{ l('Customer Orders') }}</a -->

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






{!! Form::open( ['method' => 'POST', 'id' => 'form-select-documents'] ) !!}

{!! Form::hidden('production_sheet_id', $sheet->id, array('id' => 'production_sheet_id')) !!}

<div id="div_documents">

<div class="row">

   <div class="col-lg-9 col-md-9">
   <div class="table-responsive">

@if ($sheet->productionorders->count())
<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
            <th>{{l('ID', [], 'layouts')}}</th>
            <!-- th>{{l('Product ID')}}</th -->
            <th>{{l('Product Reference')}}<br />Categoría</th>
            <th>{{l('Product Name')}}</th>
            <th>{{l('Quantity')}}</th>
            <th>{{l('Work Center')}}</th>
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
              {!! Form::checkbox('document_group[]', $order->id, false, ['class' => 'case xcheckbox']) !!}
@endif

            </td>
      <td>{{ $order->id }}</td>
      <!-- td>{{ $order->product_id }}</td -->
      <td><a href="{{ URL::to('products/' . $order->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $order->product_reference }}</a><br />
        {{ $product->category->name }}
        <a href="{{ route('categories.subcategories.edit', [-1, $product->category_id]) }}" title="{{l('View Category')}}" target="_blank">{{ $product->category_id }}</a></td>
      <td>{{ $order->product_name }}</td>
      <td>{{ $product->as_quantityable($order->planned_quantity) }}</td>
      <td>{{ $order->workcenter->name ?? '' }}</td>
      <td>{{ $order->created_via }}</td>
      <td>{{ $order->status }}</td>
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

                <a class="btn btn-sm btn-blue show-production-order-products" title="{{l('Show', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning edit-production-order" href="{{ URL::to('productionorders/' . $order->id . '/productionsheetedit') }}" title="{{l('Edit', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->product_reference }}" data-oname="{{ $order->product_name }}" data-oquantity="{{ $order->planned_quantity }}" data-oworkcenter="{{ $order->work_center_id }}" data-onotes="{{ $order->notes }}" onClick="return false;"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-production-order" href="{{ URL::to('productionorders/' . $order->id . '/productionsheetdelete') }}" title="{{l('Delete', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-trash-o"></i></a>

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

@include('layouts/back_to_top_button')



<hr style="margin:72px;border-top: 5px solid #eee;" />

@include('production_sheet_production_orders._block_legacy_stuff')

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
