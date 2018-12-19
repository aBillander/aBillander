@extends('layouts.master')

@section('title') {{ l('Customer Invoices - Edit') }} @parent @stop


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

                <a class="btn btn-sm btn-blue xbtn-info" href="{{ URL::to($model_path.'/' . $document->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-truck"></i></a>

                <a class="btn btn-sm btn-lightblue" href="{{ URL::to($model_path.'/' . $document->id . '/email') }}" title="{{l('Send to Customer', [], 'layouts')}}"><i class="fa fa-paper-plane"></i></a>

                <a class="btn btn-sm btn-info" href="{{ URL::to($model_path.'/' . $document->id . '/invoice/pdf') }}" title="{{l('PDF Invoice', [], 'layouts')}}"><i class="fa fa-money"></i></a>
                
                <a href="{{ URL::to($model_path.'') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{l('Back to Customer Invoices')}}</a>
            </div>
            
              <h2><a href="{{ URL::to($model_path.'') }}">{{l('Customer Invoices')}}</a> <span style="color: #cccccc;">/</span> 
                  {{l('Invoice to')}} <span class="lead well well-sm">

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
                 </span> &nbsp; {{ $document->document_reference }} &nbsp; <span class="badge" style="background-color: #3a87ad;" title="{{ $customer->currentPricesEnteredWithTax( $document->document_currency ) ?
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



@section('styles') @parent

    @include($view_path.'.css.panels')

@endsection


{{-- 
@section('scripts') @parent

    @include($view_path.'.js._dummy')

@endsection
 --}}
