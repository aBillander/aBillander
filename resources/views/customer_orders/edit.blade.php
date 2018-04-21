@extends('layouts.master')

@section('title') {{ l('Customer Orders - Edit') }} @parent @stop


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ URL::to('customerorders') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{l('Back to Customer Orders')}}</a>
            </div>
            
              <h2><a href="{{ URL::to('customerorders') }}">{{l('Customer Orders')}}</a> <span style="color: #cccccc;">/</span> 
                  {{l('Order to')}} <span class="lead well well-sm">

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
                 </a></span> &nbsp; {{ $order->document_reference }}
             </h2>

        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">


          <div class="panel panel-default">
          <div class="panel-body">

            <h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div>

          </div>
          </div>


      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

          @include('customer_orders._panel_customer_order')

      </div>
   </div>
</div>
@endsection
