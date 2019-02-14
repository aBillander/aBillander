@extends('layouts.master')

@section('title') {{ l('Carts') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

{{--
        <a href="{{ URL::to('customerorders/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a href="{{ route('chart.customerorders.monthly') }}" class="btn btn-sm btn-warning" 
                title="{{l('Reports', [], 'layouts')}}"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}}</a>

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
        <a class="btn btn-sm btn-grey" xstyle="margin-right: 152px" href="{{ route('fsxconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}} {{l('Enlace FactuSOL', 'layouts')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> {{l('Configuration', [], 'layouts')}}</a> 
@endif
--}}

    </div>
    <h2>
        <a href="{{ URL::to('customers') }}">{{l('Customers', 'customers')}}</a> <span style="color: #cccccc;">/</span> {{ l('Carts') }}
    </h2>        
</div>

<div id="div_carts">

   <div class="table-responsive">

@if ($carts->count())
<table id="carts" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Date Updated') }}</th>
            <th class="text-left">{{ l('Items / Quantity')}}</th>
            <th class="text-left">{{ l('Total (Tax excl.)')}}</th>
            <!-- th class="text-left">{{ l('Delivery Date') }}</th -->
            <!-- th class="text-center">{{ l('Notes') }}</th -->
            <th> </th>
        </tr>
    </thead>
    <tbody id="cart_lines">
        @foreach ($carts as $cart)
        <tr>
            
            <td><a class="" href="{{ URL::to('customers/' .$cart->customer->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
                {{ $cart->customer->name_regular }}
                </a>

                {{ $cart->customer->address->alias }} 
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $cart->customer->address->firstname }} {{ $cart->customer->address->lastname }}<br />{{ $cart->customer->address->address1 }}<br />{{ $cart->customer->address->city }} - {{ $cart->customer->address->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $cart->customer->address->phone }}</a>" data-original-title="" title="">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>

            </td>
            <td>{{ abi_date_short($cart->updated_at) }}</td>

              <td>{{ $cart->nbrItems() }} / {{ $cart->quantity }}</td>

              <td>{{ $cart->as_priceable($cart->amount) }} {{$cart->currency->sign_printable}}</td>

            <!-- td>{{ abi_date_short($cart->delivery_date) }}</td -->

            <!-- td class="text-center">@if ($cart->notes_from_customer)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($cart->notes_from_customer) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td -->
            <td class="text-right">

                <a class="btn btn-sm btn-info update-cart-prices" data-html="false" data-toggle="modal" 
                        href="{{ URL::route('carts.updateprices', [$cart->id] ) }}" 
                        data-content="{{l('You are going to UPDATE all Product Prices in this Cart. Are you sure?')}}" 
                        data-title="{{ l('Carts') }} :: ({{$cart->customer->id}}) {{ $cart->customer->name }}" 
                        onClick="return false;" title="{{l('Update Cart Prices')}}"><i class="fa fa-superpowers"></i></a>

                <a class="btn btn-sm btn-success" href="{{ URL::to('carts/' . $cart->id ) }}" title="{{l('View', [], 'layouts')}}"><i class="fa fa-eye"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $carts->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $carts->total() ], 'layouts')}} </span></li></ul>


<div name="search_filter" id="search_filter">
<div class="row" style="padding: 0 20px">

    <div class="col-md-2 xcol-md-offset-3">
    </div>





</div>
</div>



@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_carts" ENDS -->

@endsection


@include('carts/_modal_update_prices')


{{-- *************************************** --}}


@section('scripts') @parent 

<script>


    $(document).ready(function () {

          // Select first element
          // $('#production_sheet_id option:first-child').attr("selected", "selected");
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
