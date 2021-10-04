@extends('layouts.master')

@section('title') {{ l('Production Orders - Edit') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

@if ( $document->status == 'finished' )
                <button type="button" class="btn btn-sm alert-danger" title="{{l('Document closed', 'layouts')}}" style="margin-right: 16px">
                    <i class="fa fa-lock"></i>
                </button>

    @if ( $document->unfinishable )
                <a class="btn btn-sm btn-danger" href="{{ URL::to('productionorders/' . $document->id . '/unfinish') }}" title="{{l('Unclose Document', 'layouts')}}">&nbsp;<i class="fa fa-unlock"></i>&nbsp;{{l('Unclose', 'layouts')}}</a>
    @endif
@else
                <a class="btn xbtn-sm alert-success prevent-double-click" href="{{ URL::to('productionorders/' . $document->id . '/finish') }}" title="{{l('Finish Order')}}"><i class="fa fa-unlock"></i> {{l('Finish Order')}}</a>

                <a class="btn btn-sm btn-success  hide " href="{{ URL::to('productionorders/' . $document->id . '/pdf?preview') }}" title="{{l('Show Preview', [], 'layouts')}}" target="_blank"><i class="fa fa-eye"></i></a>
@endif

@if ( $document->status != 'closed' )
    @if ($document->onhold>0)
                    <a class="btn btn-sm btn-danger  hide " href="{{ URL::to('productionorders/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
    @else
                    <a class="btn btn-sm alert-info  hide " href="{{ URL::to('productionorders/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
    @endif
@else

            @if ( $document->status == 'closed' && !$document->invoiced_at)
                    @if ( $document->is_invoiceable )
                    <a class="btn btn-sm btn-navy prevent-double-click" href="{{ route('customershippingslip.invoice', [$document->id]) }}" title="{{l('Create Invoice')}}" style="margin-left: 16px; margin-right: 16px"><i class="fa fa-money"></i>
                    </a>
                    @else
                    <a class="btn btn-sm btn-navy" href="javascript::void(0);" title="{{l('Not Invoiceable Document')}}" style="opacity: 0.65; margin-left: 16px; margin-right: 16px" onclick="return false;"><i class="fa fa-money"></i>
                    </a>
                    @endif
            @endif
@endif

@if ($document->document_id>0)
                <a class="btn btn-sm btn-grey" href="{{ URL::to('productionorders/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank" onclick="open_in_new_tab_and_reload( this );return false;"><i class="fa fa-file-pdf-o"></i></a>

                <a class="btn btn-sm btn-lightblue" href="{{ URL::to('productionorders/' . $document->id . '/email') }}" title="{{l('Send to Customer', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a>
@endif

                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('productionorders/' . $document->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Documents') }} :: ({{$document->id}}) {{ $document->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif


                <div class="btn-group">
                    <a href="#" class="btn xbtn-sm btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Back to', 'layouts')}}"><i class="fa fa-mail-reply"></i> &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right">

                      <li><a href="{{ URL::to('productionorders') }}"><i class="fa fa-cubes"></i> {{l('Back to Production Orders')}}</a></li>
                      <!-- li><a href="{{ route('customer.invoiceable.shippingslips', [$document->id]) }}"><i class="fa fa-user-circle"></i> {{l('Invoice Shipping Slips')}}</a></li>
                      <li><a href="{{ route('customer.shippingslips', [$document->id]) }}"><i class="fa fa-user-circle"></i> {{l('Shipping Slips', 'layouts')}}</a></li -->

                    </ul>
                </div>


                <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>

            </div>
            
              <h2><a class="btn btn-sm alert-warning" href="{{ URL::to('productionorders') }}" title="{{l('Production Orders')}}"><i class="fa fa-cubes"></i></a> <span style="color: #cccccc;">/</span> 
                  <a href="{{ URL::to('productionorders') }}" title="{{l('Production Orders')}}">
                  	{{l('Production Order')}}</a> 

                  <span style="color: #cccccc;">/</span> #<span class="text-info">{{ $document->id }}</span> 
                  <span style="color: #cccccc;">::</span> {{ abi_date_short($document->due_date) }}  

                   <span class="badge" style="background-color: #3a87ad;" title="{{ optional($document->measureunit)->name }}"> &nbsp; {{ optional($document->measureunit)->sign }} &nbsp; </span>

                 <!-- span class="lead well well-sm">

                 </span -->

                   &nbsp; 
{{--
                    @if ($document->document_id>0)
                    {{ $document->document_reference }}

                              @if ( $document->unconfirmable )

                                  <a class="btn btn-xs alert-danger" href="{{ URL::to('productionorders/' . $document->id . '/unconfirm') }}" title="{{l('Undo Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                                  </a>

                              @endif

                    @else
                    <a class="btn xbtn-xs alert-warning" href="{{ URL::to('productionorders/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                    <span xclass="label label-default">{{ l('Draft', 'layouts') }}</span>
                    </a>
                    @endif
                   &nbsp; 
                  <span class="badge" style="background-color: #3a87ad;" title="">{{ $document->currency_iso_code }}</span>
--}}
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
             </h2>
             <h3>

        <span class="lead well well-sm alert-warning"><a href="{{ URL::to('products/' . $document->product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $document->product->reference }}</a></span>  {{ $document->product->name }}
             	
             </h3>

        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">

          @include('production_orders._panel_left_column')

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include('production_orders._panel_document')

      </div>
   </div>
</div>
@endsection

@include('production_orders._modal_delete')



{{-- *************************************** --}}


{{--  --}}

    @include('production_orders._modal_help')

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

    @include('production_orders.css.panels')

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
