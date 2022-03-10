@extends('layouts.master')

@section('title') {{ l('WooCommerce Customers') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a class=" hide btn btn-sm alert-danger" style="margin-right: 21px" href="{{ route( 'wcustomers.fetch.abi.orphans' )}}" title="{{l('aBillander Orphans')}}"> {{l('ab://ander/orphans')}}</a>


        <a class=" hide btn btn-sm btn-grey" style="margin-right: 21px" href="javascript:void(0);" title="{{l('Import', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-import').attr('action', '{{ route( 'worders.import.orders' )}}');$('#form-import').submit();return false;"><i class="fa fa-download"></i> {{l('Import', 'layouts')}}</a>


        <button  name="b_search_filter" id="b_search_filter" class=" hide btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

    <a class=" hide btn btn-sm btn-success" style="margin-right: 152px" href="{{ URL::route('wooconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}}"><i class="fa fa-cog"></i> {{l('Configuration', [], 'layouts')}} [WooC]</a> 

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
        <a href="#">{{ config('woocommerce.store_url') }}</a> <span style="color: #cccccc;">/</span> {{ l('Customers') }}
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

@if ($customers->count())

{!! Form::open( ['route' => ['productionsheet.addorders', '0'], 'method' => 'POST', 'id' => 'form-import'] ) !!}
{{-- !! csrf_field() !! --}}

   <div class="table-responsive">

<table id="orders" class="table table-hover">
	<thead>
		<tr>
      <!-- th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th -->
      <th class="text-left">{{l('ID', 'layouts')}}</th>
      <!-- th>{{l('username')}}</th -->
      <th>{{l('Created')}}</th>

      <th class="text-left">{{l('Company')}}</th>
      <th>{{l('first_name')}} {{l('last_name')}}</th>

      <th>{{l('Address')}}</th>

      <th>{{l('email')}}</th>
      <th>{{l('phone')}}</th>

      <th>{{l('orders_count')}}</th>
      <th>{{l('total_spent')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody id="order_lines">
	@foreach ($customers as $customer)

   @if ( isset($customer["abi_customer"]) && ($abi_customer = $customer["abi_customer"]) )

   {{-- Guess same --}}
   @if (
              ($customer["billing"]["email"] == trim($abi_customer->address->email)) ||
              ($customer["billing"]["address_1"] == trim($abi_customer->address->address1))

   )

        @continue

   @endif

		<tr>
			@if ( 1 )
      <!-- td> </td -->
      @else
      <td class="text-center warning">{!! Form::checkbox('worders[]', $customer['id'], false, ['class' => 'case checkbox']) !!}</td>
      @endif
      <td title="{{ $customer["username"] }}">{{ $customer["id"] }}</td>
      <!-- td>{{ $customer["username"] }}</td -->
      <td title="{{ $customer["date_created"] }}">{{ explode('T', $customer["date_created"])[0] }}</td>

      <td>{{ $customer["billing"]["company"] }}</td>
      <td>{{ $customer["billing"]["first_name"] }} {{ $customer["billing"]["last_name"] }}</td>

      <td>{{ $customer["billing"]["address_1"] }}<br />
          {{ $customer["billing"]["address_2"] }}<br />
          {{ $customer["billing"]["postcode"] }} {{ $customer["billing"]["city"] }} {{ $customer["billing"]["state"] }}
      </td>

      <td>{{ $customer["billing"]["email"] }}</td>
      <td>{{ $customer["billing"]["phone"] }}</td>


      <td>{{ $customer["orders_count"] }}</td>
      <td>{{ $customer["total_spent"] }}</td>
			</td>
		</tr>

    <tr class="danger">
      <td class="text-center">{{ $customer["abi_customer_count"] > 1 ? '*'.$customer["abi_customer_count"].'*' : '' }}</td>
      
      <td title="{{ $abi_customer->reference_external }}">{{ $abi_customer->id }}</td>

      <td title="{{ '' }}">{{ $abi_customer->name_fiscal }}</td>

      <td>{{ $abi_customer->name_commercial }}</td>

      <td>{{ $abi_customer->address->address1 }}<br />
          {{ $abi_customer->address->address2 }}<br />
          {{ $abi_customer->address->postcode }} {{ $abi_customer->address->city }} {{ $abi_customer->address->state->name }}
      </td>

      <td>{{ $abi_customer->address->email }}</td>
      <td>{{ $abi_customer->address->phone }}</td>


      <td> </td>
      <td> </td>
      </td>
    </tr>

  @else

    @continue

   @endif

	@endforeach
	</tbody>
</table>

   </div>

{{ $customers->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customers->total() ], 'layouts')}} </span></li></ul>


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
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#due_date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
  
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
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