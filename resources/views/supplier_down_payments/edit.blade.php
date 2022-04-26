@extends('layouts.master')

@section('title') {{ l('Down Payment to Suppliers - Edit') }} @parent @endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

      @if( $downpayment->deletable )
                <a class="btn xbtn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('supplierdownpayments/' . $downpayment->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Down Payment') }} :: ({{$downpayment->id}}) {{ $downpayment->reference ?: $downpayment->id }} - {{ \App\Models\Currency::viewMoneyWithSign($downpayment->amount, $downpayment->currency) }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
      @endif

      @if ($downpayment->supplierinvoice)

                <a class="btn xbtn-sm btn-navy" style="margin-right: 36px; margin-left: 36px;"href="{{ route('supplierinvoices.edit', [$downpayment->supplierinvoice->id]) }}" title="{{l('Go to', 'layouts')}}"><i class="fa fa-money"></i> &nbsp;{{ l('Invoice')}}: {{ $downpayment->supplierinvoice->document_reference ?: l('Draft', 'layouts') }}
                </a>

      @endif

      @if ($downpayment->status != 'applied')

            <a class=" hide  btn xbtn-sm btn-blue" style="margin-right: 36px; margin-left: 36px;" href="{{ URL::to('supplierdownpayments/' . $downpayment->id  . '/pay' ) }}" title="{{l('Deposit Downpayment')}}"><i class="fa fa-money"></i> &nbsp;{{l('Deposit Downpayment')}}
            </a>

      @endif

            </div>

            <h2><a href="{{ URL::to('supplierdownpayments') }}">{{ l('Down Payment to Suppliers') }}</a> <span style="color: #cccccc;">/</span> <span class="lead well well-sm alert-warning">{{ $downpayment->reference ?: $downpayment->id }}</span> 

               {{ $downpayment->as_money_amount('amount') }} 
               
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $downpayment->currency->name }}">{{ $downpayment->currency->iso_code }}</span>


              @if     ( $downpayment->status == 'pending' )
                <span class="badge alert-info">
              @elseif ( $downpayment->status == 'deposited' )
                <span class="badge alert-danger">
              @elseif ( $downpayment->status == 'applied' )
                <span class="badge alert-success">
              @elseif ( $downpayment->status == 'bounced' )
                <span class="label alert-danger">
              @else
                <span class="label">
              @endif
              {{ $downpayment->status_name }}</span>

               <span style="color: #cccccc;">/</span> 

               [<a class="" href="{{ URL::to('supplierorders/' .$document->id . '/edit') }}" title="{{ l('Go to', 'layouts') }}" target="_new">{{ $document->document_reference ?: l('Draft', 'layouts').' - '.$document->id }}</a>]

            </h2><h2>

                  <a href="{{ URL::to('suppliers/' . $supplier->id . '/edit') }}" title=" {{l('View Supplier')}} " target="_blank">{{ $supplier->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address', 'supplierinvoices')}}" data-content="
                                  {{$supplier->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$supplier->identification}}<br />
                                  {{ $supplier->address->address1 }} {{ $supplier->address->address2 }}<br />
                                  {{ $supplier->address->postcode }} {{ $supplier->address->city }}, {{ $supplier->address->state->name }}<br />
                                  {{ $supplier->address->country->name }}
                                  <br />
                            ">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a>
            </h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_main" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
            <a id="b_details" href="#details" class="list-group-item">
               <i class="fa fa-tasks"></i>
               &nbsp; {{ l('Details') }}
            </a>
            <a id="b_attachments" href="#attachments" class="list-group-item">
               <i class="fa fa-paperclip"></i>
               &nbsp; {{ l('Attachments', 'layouts') }}
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">
            
          @include('supplier_down_payments._panel_main_data')

          @include('supplier_down_payments._panel_details')

          @include('supplier_down_payments._panel_attachments')


      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>
@endsection

@section('scripts')     @parent
<script type="text/javascript">
   function route_url()
   {
      $("#panel_main").hide();
      $("#panel_details").hide();
      $("#panel_attachments").hide();

      $("#b_main").removeClass('active');
      $("#b_details").removeClass('active');
      $("#b_attachments").removeClass('active');
      
      if(window.location.hash.substring(1) == 'details')
      {
         $("#panel_details").show();
         $("#b_details").addClass('active');
         getDownPaymentDetails();
         // document.f_cliente.codgrupo.focus();
      }
      else if(window.location.hash.substring(1) == 'attachments')
      {
         $("#panel_attachments").show();
         $("#b_attachments").addClass('active');
         // getSupplierUsers();
      }
      else  
      {
         $("#panel_main").show();
         $("#b_main").addClass('active');
         // document.f_cliente.nombre.focus();
      }

      // Gracefully scrolls to the top of the page
      $("html, body").animate({ scrollTop: 0 }, "slow");
   }
   
   $(document).ready(function() {
      route_url();
      window.onpopstate = function(){
         route_url();
      }
   });

</script>
@endsection

@include('layouts/modal_delete')
