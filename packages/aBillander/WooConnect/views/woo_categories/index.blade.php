@extends('layouts.master')

@section('title') {{ l('WooCommerce Categories') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a class="btn btn-sm alert-success" style="margin-right: 76px" href="{{ URL::route('wproducts.index') }}" title="{{l('Products', [], 'layouts')}}"><i class="fa fa-list"></i> {{l('Products')}} [WooC]</a>

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
        <a href="#">{{ config('woocommerce.store_url') }}</a> <span style="color: #cccccc;">/</span> {{ l('Categories') }}
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

@if ($categories->count())

{!! Form::open( ['route' => ['productionsheet.addorders', '0'], 'method' => 'POST', 'id' => 'form-import'] ) !!}
{{-- !! csrf_field() !! --}}

   <div class="table-responsive">

<table id="orders" class="table table-hover">
	<thead>
		<tr>
      <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
      <th class="text-left">{{l('ID', 'layouts')}}</th>
      <th class="text-left">{{l('Parent ID')}}</th>
      <th colspan="2"><span style="font-weight: normal !important">{{l('Category Name')}}</span><br />{{l('Slug')}}</th>
      <!-- th>{{l('Slug')}}</th -->
      <th>{{l('Description')}}</th>
      <th>{{l('Display')}}</th>
      <th>{{l('Menu Order')}}</th>

      <th>{{l('Products Count')}}</th>

      <th>{{l('Local Category')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody id="order_lines">
	@foreach ($categories as $category)

		<tr>
			@if ( $category["imported_at"] ?? 0 )
      <td> </td>
      @else
      <td class="text-center warning">{!! Form::checkbox('worders[]', $category['id'], false, ['class' => 'case checkbox']) !!}</td>
      @endif
      <td>{{ $category["id"] }}</td>
      <td>{{ $category["parent"] }}</td>
      <td>@if ( optional($category['image'])['src'] ) <img height="32px" src="{{ optional($category['image'])['src'] }}" style="border: 1px solid #dddddd;"> @endif
      </td>
      <td>{{ $category["name"] }}<br /><strong>{{ $category["slug"] }}</strong></td>

      <td class="text-center" style="width:1px; white-space: nowrap;">
      @if ($category['description'])
       <a href="javascript:void(0);">
          <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                  data-content="{{ $category['description'] }}">
              <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
          </button>
       </a>
      @endif</td>

      <td>{{ $category["display"] }}</td>
      <td>{{ $category["menu_order"] }}</td>
      <td>{{ $category["count"] }}</td>
      <td>
        @if ( $category["abi_category"] )
            [{{ $category["abi_category"]->id }}] 
            <a class="link" href="{{ route('categories.subcategories.edit', [$category["abi_category"]->parent_id, $category["abi_category"]->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"> {{ $category["abi_category"]->name }}</a>
        @endif
      </td>
			<td class="text-right" style="width:1px; white-space: nowrap;">

                <a class='update-local-category btn btn-sm btn-warning' href="{{ URL::route('wcategories.ascription') }}"
                    data-target='#myModalAscribe' 
                    data-id="{{ $category['id'] }}" 
                    data-name="{{ $category['name'] }}" 
                    data-slug="{{ $category['slug'] }}" 
                    data-localid="{{ $category['abi_category'] ? $category['abi_category']->id : '' }}" 
                    data-toggle="modal" onClick="return false;" 
                    title="{{l('Update', [], 'layouts')}}"><i class="fa fa-pencil-square-o"></i></a>                

                <a class="btn btn-sm btn-blue" href="{{ URL::route('wcategories.fetch', $category["id"] ) }}" title="{{l('Fetch', [], 'layouts')}}" target="_blank"><i class="fa fa-eyedropper"></i></a>

{{--
                <a class="btn btn-sm btn-info" href="{{ URL::route('wcategories.fetch', [$category["id"]] ) }}" title="{{l('Fetch', [], 'layouts')}}" target="_blank"><i class="fa fa-superpowers"></i></a>

                <a class="btn btn-sm btn-success" href="{{ URL::to('wooc/worders/' . $category["id"]) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>
      
      @if ( $category["imported_at"] ?? 0 )
                <a class="btn btn-sm btn-lightblue" href="{{ URL::route('customerorders.edit', [$category["abi_order_id"]] ) }}" title="aBillander :: {{l('Show', [], 'layouts')}}"><i class="fa fa-file-text-o"></i></a>
      @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('worders.import', [$category["id"]] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-download"></i></a>
      @endif

                <!-- a class="btn btn-sm btn-info" href="{{ URL::route('worders.invoice', [$category["id"]] ) }}" title="{{l('Invoice', [], 'layouts')}}"><i class="fa fa-file-text"></i></a -->

                <!-- a class='open-deleteDialog btn btn-danger' data-target='#myModal1' data-id="{{ $category["id"] }}" data-toggle='modal'>{{l('Delete', [], 'layouts')}}</a -->
--}}
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

   </div>

{{ $categories->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $categories->total() ], 'layouts')}} </span></li></ul>


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

<div class="modal fade" id="myModalAscribe" role="dialog">

   <div class="modal-dialog">



       <!-- Modal content-->

       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal">&times;</button>

               <h4 class="modal-title">{{l('Set Local Category')}}</h4>

           </div>

           <div class="modal-body">

               <!-- p>Some text in the modal.</p -->


{!! Form::open(array('url' => '', 'method' => 'POST', 'id' => 'woo_category_ascription', 'name' => 'woo_category_ascription', 'class' => 'form')) !!}

                  
<div class="row">
		<div class="form-group col-lg-6 col-md-6 col-sm-6">
                           
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">{{l('WooCommerce Category')}}</h3>
          </div>
          <div class="panel-body">
            <div id="category-label"></div>
            {!! Form::hidden('webshop_id', null, array('id' => 'webshop_id')) !!}
          </div>
        </div>

		</div>

		<div class="form-group col-lg-6 col-md-6 col-sm-6">
                           
        <div class="panel panel-success">
          <div class="panel-heading">
            <h3 class="panel-title">{{l('Local Category')}}</h3>
          </div>
          <div class="panel-body {{ $errors->has('category_id') ? 'has-error' : '' }}">
                    {!! Form::select('category_id', array('' => l('-- Please, select --', [], 'layouts')) + $categoryList, null, array('class' => 'form-control', 'id' => 'category_id')) !!}
                    {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}

                    {!! Form::hidden('current_category_id', null, array('id' => 'current_category_id')) !!}
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
          // $(document).on("click", ".update-local-category", function() {
            $('.update-local-category').click(function (evnt) {

               var href = $(this).attr('href');
               var cId = $(this).attr('data-id');
               var name = $(this).attr('data-name');
               var slug = $(this).attr('data-slug');
               var abiId = $(this).attr('data-localid');

               var label = '[' + cId + '] ' + name + '<br />{{l('Slug')}}: <strong>' + slug + '</strong>';

               $('#woo_category_ascription').attr('action', href);
               $("#category-label").html(label);
               $("#webshop_id").val(cId);
               $("#current_category_id").val(abiId);
               $("#category_id").val(abiId);

               // https://blog.revillweb.com/jquery-disable-button-disabling-and-enabling-buttons-with-jquery-5e3ffe669ece
               // $('#btn-update').prop('disabled', false);

               $('#myModalAscribe').modal({show: true});

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