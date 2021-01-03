@extends('layouts.master')

@section('title') {{ l('Documents - Edit') }} @parent @stop


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
                <a class="btn btn-sm btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/unclose') }}" title="{{l('Unclose Document', 'layouts')}}">&nbsp;<i class="fa fa-unlock"></i>&nbsp;{{l('Unclose', 'layouts')}}</a>
    @endif
@else
                <a class="btn btn-sm alert-success" href="{{ URL::to($model_path.'/' . $document->id . '/close') }}" title="{{l('Close Document', 'layouts')}}"><i class="fa fa-unlock"></i> {{l('Close', 'layouts')}}</a>
@endif

                <a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id . '/pdf?preview') }}" title="{{l('Show Preview', [], 'layouts')}}" target="_blank"><i class="fa fa-eye"></i></a>

@if (0 && $document->document_id>0)
                <a class="btn btn-sm btn-lightblue" href="{{ URL::to($model_path.'/' . $document->id . '/email') }}" title="{{l('Send to Supplier', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
@endif

@if ( $document->status != 'closed' )
    @if ($document->onhold>0)
                    <a class="btn btn-sm btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
    @else
                    <a class="btn btn-sm alert-info" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
    @endif
@else

                    @if ( $document->status == 'closed' && !$document->invoiced_at)
                    <a class="btn btn-sm btn-navy" href="{{ route('suppliershippingslip.invoice', [$document->id]) }}" title="{{l('Create Invoice')}}"><i class="fa fa-money"></i>
                    </a>
                    @endif
@endif

                @if( $document->deletable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to($model_path.'/' . $document->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Documents') }} :: ({{$document->id}}) {{ $document->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif


                <div class="btn-group">
                    <a href="#" class="btn xbtn-sm btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Back to', 'layouts')}}"><i class="fa fa-mail-reply"></i> &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right">
                      <li><a href="{{ URL::to($model_path.'') }}"><i class="fa {{ $model_class::getBadge('i_class') }}"></i> {{l('Back to Documents')}}</a></li>
                      <!-- li><a href="{ { route('supplier.invoiceable.shippingslips', [$supplier->id]) }}"><i class="fa fa-user-circle"></i> {{l('Group Shipping Slips')}}</a></li -->
                      <li><a href="{{ route('supplier.shippingslips', [$supplier->id]) }}"><i class="fa fa-user-circle"></i> {{l('Shipping Slips', 'layouts')}}</a></li>
                      <!-- li class="divider"></li -->
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>


                <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>

            </div>
            
              <h2><a class="btn btn-sm {{ $model_class::getBadge('a_class') }}" href="{{ URL::to($model_path.'') }}" title="{{l('Documents')}}"><i class="fa {{ $model_class::getBadge('i_class') }}"></i></a> <span style="color: #cccccc;">/</span> 
                  {{l('Document from')}} <span class="lead well well-sm">

                  <a href="{{ URL::to('suppliers/' . $supplier->id . '/edit') }}" title=" {{l('View Supplier')}} " target="_blank">{{ $supplier->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address')}}" data-content="
                                  {{$supplier->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$supplier->identification}}<br />
                                  {{ $invoicing_address->address1 }} {{ $invoicing_address->address2 }}<br />
                                  {{ $invoicing_address->postcode }} {{ $invoicing_address->city }}, {{ $invoicing_address->state->name }}<br />
                                  {{ $invoicing_address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
                 @if($supplier->sales_equalization)
                  <span id="sales_equalization_badge" class="badge" title="{{l('Equalization Tax')}}"> RE </span>
                 @endif
                 </span>
                   &nbsp; 
                    @if ($document->document_id>0)
                    {{ $document->document_reference }}

                              @if ( $document->unconfirmable )

                                  <a class="btn btn-xs alert-danger" href="{{ URL::to($model_path.'/' . $document->id . '/unconfirm') }}" title="{{l('Undo Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                                  </a>

                              @endif

                    @else
                    <a class="btn xbtn-xs alert-warning" href="{{ URL::to($model_path.'/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                    <span xclass="label label-default">{{ l('Draft', 'layouts') }}</span>
                    </a>
                    @endif
                   &nbsp; 
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $supplier->currentPricesEnteredWithTax( $document->document_currency ) ?
                                                        l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                                        l('Prices are entered exclusive of tax', [], 'appmultilang') }}">{{ $document->currency->iso_code }}</span>
                 {{-- https://codepen.io/MarcosBL/pen/uomCD --}}
             </h2>

        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">

          @include($view_path.'._panel_left_column')

      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include($view_path.'._panel_document')

      </div>
   </div>
</div>
@endsection

@include($view_path.'._modal_delete')



{{-- *************************************** --}}


{{--  --}}

    @include($view_path.'._modal_help')

{{--  --}}


@section('styles') @parent

    @include($view_path.'.css.panels')

@endsection


{{-- 
@section('scripts') @parent

    @include($view_path.'.js._dummy')

@endsection
 --}}
