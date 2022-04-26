@extends('layouts.master')

@section('title') {{ l('Documents - Edit') }} @parent @endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

@if ( $document->status == 'closed' )
                <button type="button" class="btn btn-sm alert-danger" title="{{l('Document closed', 'layouts')}}" style="margin-right: 16px">
                    <i class="fa fa-lock"></i>
                </button>

    @if ( $document->uncloseable )
                <a class="btn btn-sm btn-danger" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/unclose') }}" title="{{l('Unclose Document', 'layouts')}}">&nbsp;<i class="fa fa-unlock"></i>&nbsp;{{l('Unclose', 'layouts')}}</a>
    @endif
@else
                <a class="btn btn-sm alert-success prevent-double-click" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/close') }}" title="{{l('Close Document', 'layouts')}}"><i class="fa fa-unlock"></i> {{l('Close', 'layouts')}}</a>

                <a class="btn btn-sm btn-success" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/pdf?preview') }}" title="{{l('Show Preview', [], 'layouts')}}" target="_blank"><i class="fa fa-eye"></i></a>
@endif

@if ( $document->status != 'closed' )
    @if ($document->onhold>0)
                    <a class="btn btn-sm btn-danger" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
    @else
                    <a class="btn btn-sm alert-info" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
    @endif
@else

            @if ( $document->status == 'closed' && !$document->invoiced_at)
            @endif
@endif

@if ($document->document_id>0)
                <a class="btn btn-sm btn-grey" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank" onclick="open_in_new_tab_and_reload( this );return false;"><i class="fa fa-file-pdf-o"></i></a>

                <!-- a class="btn btn-sm btn-lightblue" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/email') }}" title="{{l('Send to Customer', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a -->
@endif

                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('warehouseshippingslips/' . $document->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Documents') }} :: ({{$document->id}}) {{ $document->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif


                <div class="btn-group">
                    <a href="#" class="btn xbtn-sm btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Back to', 'layouts')}}"><i class="fa fa-mail-reply"></i> &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right">
                      <li><a href="{{ URL::to('warehouseshippingslips') }}"><i class="fa {{ \App\Models\WarehouseShippingSlip::getBadge('i_class') }}"></i> {{l('Back to Documents')}}</a></li>
                      <li class="divider"></li>

                      <li><a href="{{ route('warehouse.inventory', [$document->warehouse_id]) }}" target="_blank"><i class="fa fa-th-list" style="color: #38b44a;"></i> {{ l('Products in') }} [{{ $document->warehouse->alias }}] {{ $document->warehouse->name }}</a></li>

                      <li><a href="{{ route('warehouse.inventory', [$document->warehouse_counterpart_id]) }}" target="_blank"><i class="fa fa-th-list" style="color: #38b44a;"></i> {{ l('Products in') }} [{{ $document->warehousecounterpart->alias }}] {{ $document->warehousecounterpart->name }}</a></li>
                      <!-- li class="divider"></li -->
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>


                <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>

            </div>
            
              <h2><a class="btn btn-sm {{ \App\Models\WarehouseShippingSlip::getBadge('a_class') }}" href="{{ URL::to('warehouseshippingslips') }}" title="{{l('Documents')}}"><i class="fa {{ \App\Models\WarehouseShippingSlip::getBadge('i_class') }}"></i></a> <span style="color: #cccccc;">/</span> 
                  
                  <span class="lead well well-sm">

                  <a href="{{ URL::to('warehouses/' . $document->warehouse_id . '/edit') }}" title=" {{l('View Warehouse')}} " target="_blank">[{{ $document->warehouse->alias }}] {{ $document->warehouse->name }}</a>

                 <a title=" {{l('View Warehouse Address')}} " href="javascript:void(0);">
{{--
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{1entification}}<br />
                                  {{ $invoicing_address->address1 }} {{ $invoicing_address->address2 }}<br />
                                  {{ $invoicing_address->postcode }} {{ $invoicing_address->city }}, {{ $invoicing_address->state->name }}<br />
                                  {{ $invoicing_address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
--}}
                 </a>

                 </span> 

                  <i class="fa fa-long-arrow-right" style="color: #bbbbbb;"></i>

                  <span class="lead well well-sm">

                  <a href="{{ URL::to('warehouses/' . $document->warehouse_counterpart_id . '/edit') }}" title=" {{l('View Warehouse')}} " target="_blank">[{{ $document->warehousecounterpart->alias }}] {{ $document->warehousecounterpart->name }}</a>

                 <a title=" {{l('View Warehouse Address')}} " href="javascript:void(0);">
{{--
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{1entification}}<br />
                                  {{ $invoicing_address->address1 }} {{ $invoicing_address->address2 }}<br />
                                  {{ $invoicing_address->postcode }} {{ $invoicing_address->city }}, {{ $invoicing_address->state->name }}<br />
                                  {{ $invoicing_address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
--}}
                 </a>

                 </span>


                   &nbsp; 
                    @if ($document->document_id>0)
                    {{ $document->document_reference }}

                              @if ( $document->unconfirmable )

                                  <a class="btn btn-xs alert-danger" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/unconfirm') }}" title="{{l('Undo Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                                  </a>

                              @endif

                    @else
                    <a class="btn xbtn-xs alert-warning" href="{{ URL::to('warehouseshippingslips/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                    <span xclass="label label-default">{{ l('Draft', 'layouts') }}</span>
                    </a>
                    @endif
                   &nbsp; 
                  <!-- span class="badge" style="background-color: #3a87ad;" title="{ { $customer->currentPricesEnteredWithTax( $document->document_currency ) ?
                                                        l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                                        l('Prices are entered exclusive of tax', [], 'appmultilang') } }">{ { $document->currency->iso_code } }</span -->
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
             </h2>

        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">

          @include('warehouse_shipping_slips._panel_left_column')

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include('warehouse_shipping_slips._panel_document')

      </div>
   </div>
</div>
@endsection

@include('warehouse_shipping_slips._modal_delete')



{{-- *************************************** --}}


{{--  --}}

    @include('warehouse_shipping_slips._modal_help')

{{--  --}}



@section('scripts') @parent

<script type="text/javascript">

$(document).ready(function() {
    // https://stackoverflow.com/questions/1681679/disabling-links-to-stop-double-clicks-in-jquery

    $("a.prevent-double-click").one("click", function() {
        $(this).click(function () { return false; });
    });

});


   // Open in new tab & reload

    function open_in_new_tab_and_reload(link)
    {
      // Open in new tab
      var href = link.href;
      window.open(href, '_blank');
      //focus to that window
      window.focus();
      //reload current page
      // Delay
      setTimeout(function() {
          // alert('Was called after 2 seconds');
          location.reload();
      }, 4000);
      
    }

</script>

@endsection


@section('styles') @parent

    @include('warehouse_shipping_slips.css.panels')

{{-- Stop Bootstrap drop menu's being cut off in responsive tables

    https://dcblog.dev/stop-bootstrap-drop-menus-being-cut-off-in-responsive-tables
--}}

<style type="text/css">
    @media (max-width: 767px) {
        .table-responsive .dropdown-menu {
            position: static !important;
        }
    }
    @media (min-width: 768px) {
        .table-responsive {
            overflow: visible;
        }
    }
</style>

@endsection

