@extends('layouts.master')

@section('title') {{ l('Documents - Edit') }} @parent @stop


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

@if ( $document->status == 'closed' )
                <a class="btn btn-sm btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/unclose') }}" title="{{l('Unclose Document', 'layouts')}}">&nbsp;<i class="fa fa-lock"></i>&nbsp;{{l('Unclose', 'layouts')}}</a>
@else
                <a class="btn btn-sm alert-success" href="{{ URL::to($model_path.'/' . $document->id . '/close') }}" title="{{l('Close Document', 'layouts')}}"><i class="fa fa-unlock"></i> {{l('Close', 'layouts')}}</a>

                <a class="btn btn-sm btn-success" href="{{ URL::to($model_path.'/' . $document->id . '/pdf?preview') }}" title="{{l('Show Preview', [], 'layouts')}}" target="_blank"><i class="fa fa-eye"></i></a>
@endif

@if ($document->document_id>0)
                <a class="btn btn-sm btn-lightblue" href="{{ URL::to($model_path.'/' . $document->id . '/email') }}" title="{{l('Send to Customer', [], 'layouts')}}" onclick="fakeLoad();this.disabled=true;"><i class="fa fa-envelope"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
@endif

@if ($document->onhold>0)
                <a class="btn btn-sm btn-danger" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Unset on-hold', 'layouts')}}"><i class="fa fa-toggle-off"></i></a>
@else
                @if ( $document->status != 'closed' )
                <a class="btn btn-sm alert-info" href="{{ URL::to($model_path.'/' . $document->id . '/onhold/toggle') }}" title="{{l('Set on-hold', 'layouts')}}"><i class="fa fa-toggle-on"></i></a>
                @endif
@endif


                <div class="btn-group">
                    <a href="#" class="btn xbtn-sm btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Back to', 'layouts')}}"><i class="fa fa-mail-reply"></i> &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right"">
                      <li><a href="{{ URL::to($model_path.'') }}">{{l('Back to Documents')}}</a></li>
                      <li><a href="{{ route('customer.shippingslips', [$customer->id]) }}">{{l('Group Shipping Slips')}}</a></li>
                      <!-- li class="divider"></li -->
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>


                <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>

            </div>
            
              <h2><a class="btn btn-sm {{ $model_class::getBadge('a_class') }}" href="{{ URL::to($model_path.'') }}" title="{{l('Documents')}}"><i class="fa {{ $model_class::getBadge('i_class') }}"></i></a> <span style="color: #cccccc;">/</span> 
                  {{l('Document to')}} <span class="lead well well-sm">

                  <a href="{{ URL::to('customers/' . $customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{{ $customer->name_fiscal }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$customer->identification}}<br />
                                  {{ $invoicing_address->address1 }} {{ $invoicing_address->address2 }}<br />
                                  {{ $invoicing_address->postcode }} {{ $invoicing_address->city }}, {{ $invoicing_address->state->name }}<br />
                                  {{ $invoicing_address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
                 @if($customer->sales_equalization)
                  <span id="sales_equalization_badge" class="badge" title="{{l('Equalization Tax')}}"> RE </span>
                 @endif
                 </span>
                   &nbsp; 
                    @if ($document->document_id>0)
                    {{ $document->document_reference }}
                              @if ( $document->sequence )
                              @if ( ($document->sequence->next_id - $document->document_id) == 1 )
                                  <a class="btn btn-xs alert-danger" href="{{ URL::to($model_path.'/' . $document->id . '/unconfirm') }}" title="{{l('Undo Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                                  </a>
                              @endif
                              @endif
                    @else
                    <a class="btn xbtn-xs alert-warning" href="{{ URL::to($model_path.'/' . $document->id . '/confirm') }}" title="{{l('Confirm', [], 'layouts')}}"><i class="fa fa-hand-stop-o"></i>
                    <span xclass="label label-default">{{ l('Draft', 'layouts') }}</span>
                    </a>
                    @endif
                   &nbsp; 
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $customer->currentPricesEnteredWithTax( $document->document_currency ) ?
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


          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Customer Infos') }}</h4>
              </li>
              <li class="list-group-item">
                {{l('Customer Group')}}:<br /> {{ $customer->customergroup->name ?? '-' }}
              </li>
              <li class="list-group-item">
                {{l('Price List')}}:<br /> {{ $customer->pricelist->name ?? '-' }}
              </li>
              <li class="list-group-item">
                {{l('Sales Representative')}}:<br />
                @if( $document->salesrep )
                <a href="{{ URL::to('salesreps/' . $document->salesrep->id . '/edit') }}" target="_new">{{ $document->salesrep->name }}</a>
                @else
                -
                @endif
              </li>
              <li class="list-group-item">
                {{l('Equalization Tax')}}:<br />
                @if( $document->customer->sales_equalization > 0 )
                {{l('Yes', 'layouts')}}
                @else
                {{l('No', 'layouts')}}
                @endif
              </li>

              <!-- li class="list-group-item">
                <h4 class="list-group-item-heading">{{l('Customer Group')}}</h4>
                <p class="list-group-item-text">{{ $customer->customergroup->name ?? '' }}</p>
              </li>
              <li class="list-group-item">
                <h4 class="list-group-item-heading">{{l('Price List')}}</h4>
                <p class="list-group-item-text">{{ $customer->pricelist->name ?? '' }}</p>
              </li -->
            </ul>

          </div>
          </div>


      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include($view_path.'._panel_document')

      </div>
   </div>
</div>
@endsection



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
