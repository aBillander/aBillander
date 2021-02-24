@extends('layouts.master')

@section('title') {{ l('Documents') }} @parent @stop


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

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>


        <a href="{{ URL::to($model_path.'/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        
        <div class="btn-group xopen">
          <a href="{{ route($model_path.'.index') }}" class="btn btn-success btn-sm" title="{{l('Filter Records', [], 'layouts')}}"><i class="fa fa-filter"></i> &nbsp;{{l('All', [], 'layouts')}}</a>

          <a href="#" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></a>

          <ul class="dropdown-menu">
            <li><a href="{{ route($model_path.'.index', ['closed' => '0']) }}"><i class="fa fa-exclamation-triangle text-danger"></i> &nbsp; {{l('Not Closed')}}</a>
            </li>

            <li><a href="{{ route($model_path.'.index', ['closed' => '1']) }}"><i class="fa fa-truck text-muted"></i> &nbsp; {{l('Closed')}}</a>
            </li>

            <li class="divider"></li>
          </ul>
        </div>

        <a href="{{ route('chart.customerorders.monthly') }}" class="btn btn-sm btn-warning" 
                title="{{l('Reports', [], 'layouts')}}"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}}</a>

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
        <a class="btn btn-sm btn-grey" xstyle="margin-right: 152px" href="{{ route('fsxconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}} {{l('Enlace FactuSOL', 'layouts')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> {{l('Configuration', [], 'layouts')}}</a>
@endif

    </div>
    <h2>
        {{ l('Documents') }}
    </h2>
</div>



{{-- Search Stuff --}}

          @include($view_path.'.index_form_search')

{{-- Search Stuff - ENDS --}}



<div id="div_documents">

   <div class="table-responsive">

@if ($documents->count())
<table id="documents" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('ID', 'layouts') }}</th>
            <th class="text-center"></th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Deliver to') }}
              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('Address is displayed if it is different from Customer Main Address') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </th>
            <th class="text-left">{{ l('Created via') }}</th>
            <th class="text-right">{{ l('Total') }}</th>
            <th class="text-center">{{ l('Notes', 'layouts') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="document_lines">
        @foreach ($documents as $document)
        <tr>
            <td>{{ $document->id }} / 
                @if ($document->document_id>0)
                {{ $document->document_reference }}
                @else
                <a class="btn btn-xs btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                <span xclass="label label-default">{{ l('Draft') }}</span>
                </a>
                @endif</td>
            <td class="text-center button-pad">

@if ($document->invoiced_at)
                <a class="btn btn-xs btn-success" href="{{ URL::to('customerinvoices/' . $document->customerinvoice()->id . '/edit') }}" title="{{abi_date_short( $document->invoiced_at )}}"><i class="fa fa-money"></i></a>
@else
    @if ( $document->status == 'closed' )
                <a class="btn btn-xs alert-danger" href="#" title="{{l('Document closed', 'layouts')}}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-lock"></i>&nbsp;</a>
    @else
        @if ($document->onhold>0)
                    <a class="btn btn-xs btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
        @else
                    <a class="btn btn-xs alert-info" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
        @endif
    @endif
@endif

@if ( $document->edocument_sent_at )
                <a class="btn btn-xs alert-success" href="#" title="{{l('Email sent:')}} {{ abi_date_short($document->document_date) }}" onclick="return false;" onfocus="this.blur();">&nbsp;<i class="fa fa-envelope-o"></i>&nbsp;</a>
@endif
              
@if ($document->export_date)
                <a class="btn btn-xs btn-grey" href="javascript:void(0);" title="{{l('Exportado el:')}} {{ abi_date_short($document->export_date) }}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
@endif
                
            </td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>{{ abi_date_short($document->delivery_date) }}</td>
            <td><a class="" href="{{ URL::to('customers/' . optional($document->customer)->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ optional($document->customer)->name_regular }}
            	</a>
            </td>
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
            <td>{{ $document->created_via }}
            </td>
            <td class="text-right">{{ $document->as_money_amount('total_tax_incl') }}</td>
            <td class="text-center">@if ($document->all_notes)
                 <a href="javascript:void(0);">
                    <button type="button" 
                        @if ($document->notes && 0)
                            class="btn xbtn-sm xbtn-success alert-danger" xstyle="background-color: #f2dede;"
                        @endif
                         data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($document->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
            <td class="text-right button-pad">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customeror ders/' . $document->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{ { URL::to('customer orders/' . $document->id) } }" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->
@if ( 0 )

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

@if ($document->onhold>0 || 1)

@else

                @if ( $document->status == 'closed' && !$document->invoiced_at)
                <a class="btn btn-sm btn-navy" href="{{ route('customershippingslip.invoice', [$document->id]) }}" title="{{l('Create Invoice')}}"><i class="fa fa-money"></i>
                </a>
                @endif
@endif

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
                @if ($document->export_date)
                <a class="btn btn-sm btn-default" style="display:none;" href="javascript:void(0);" title="{{$document->export_date}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('fsxorders.export', [$document->id] ) }}" title="{{l('Exportar a FactuSOL')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @endif
@endif

                <a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id . '/duplicate') }}" title="{{l('Copy Order')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to($model_path.'/' . $document->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to($model_path.'/' . $document->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Documents') }} :: <span class='btn btn-xs btn-grey'> {{ $document->document_reference != '' ? $document->document_reference : $document->id}} </span> &nbsp; <b>{{ $document->as_money_amount('total_tax_incl') }}</b> &nbsp; {{ $document->customer->name_regular }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- $documents->appends( collect(Request::all())
                            ->map(function($item) {
                                    // Take empty keys, otherwise skipped!
                                    return is_null($item) ? 1 : $item;
                            })->toArray() )->render() --}}
{!! $documents->appends( Request::all() )->render() !!}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $documents->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div><!-- div class="table-responsive" ENDS -->

</div><!-- div id="div_documents" ENDS -->

@include('layouts/back_to_top_button')

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
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });

      // Select first element
      $('#production_sheet_id option:first-child').attr("selected", "selected");
});

</script>

{{-- Auto Complete --}}
{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(document).ready(function() {
{{-- --}}
        $("#autocustomer_name").autocomplete({
            source : "{{ route('home.searchcustomer') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customer_id = value.item.id;

                $("#autocustomer_name").val(value.item.name_regular);
                $("#customer_id").val(value.item.id);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_regular + "</div>" )
                .appendTo( ul );
            };
{{-- --}}

    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });


    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

{{-- --}}
   $('#process').submit(function(event) {

     if ( $("#autocustomer_name").val() == '' ) $('#customer_id').val('');

     return true;

   });
{{-- --}}
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
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
    .ui-datepicker { z-index: 10000 !important; }


/* Undeliver dropdown effect */
   .hover-item:hover {
      background-color: #d3d3d3 !important;
    }

</style>

@endsection
