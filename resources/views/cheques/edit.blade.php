@extends('layouts.master')

@section('title') {{ l('Customer Cheques - Edit') }} @parent @stop


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

            <a class="btn xbtn-sm alert-danger" xstyle="margin-right: 36px; margin-left: 36px;" href="{{ URL::to('cheques/' . $cheque->id  . '/voucherduedates' ) }}" title="{{l('Set Voucher Due Dates according to Cheque Due Date')}}"><i class="fa fa-calendar"></i> &nbsp;{{l('Set Voucher Due Dates')}}
            </a>

      @if ($cheque->status != 'paid')

            <a class="btn xbtn-sm btn-blue" style="margin-right: 36px; margin-left: 36px;" href="{{ URL::to('cheques/' . $cheque->id  . '/pay' ) }}" title="{{l('Deposit Cheque')}}"><i class="fa fa-money"></i> &nbsp;{{l('Deposit Cheque')}}
            </a>
      @else

            <a href="{{ route('cheque.bounce', [$cheque->id]) }}" class="btn xbtn-sm btn-danger" style="margin-right: 36px; margin-left: 36px;" 
            title="{{l('Bounce Cheque')}}" xstyle="margin-left: 22px;"><i class="fa fa-mail-reply-all"></i> &nbsp;{{l('Bounce Cheque')}}</a>

      @endif

            </div>

            <h2><a href="{{ URL::to('cheques') }}">{{ l('Customer Cheques') }}</a> <span style="color: #cccccc;">/</span> <span class="lead well well-sm alert-warning">{{$cheque->document_number}}</span> 

               {{ $cheque->as_money_amount('amount') }} 
               
                  <span class="badge" style="background-color: #3a87ad;" title="{{ $cheque->currency->name }}">{{ $cheque->currency->iso_code }}</span>

               <span style="color: #cccccc;">/</span> 

            </h2><h2>

                  <a href="{{ URL::to('customers/' . $customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{{ $customer->name_regular }}</a>

                 <a title=" {{l('View Invoicing Address')}} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success" data-toggle="popover" data-placement="right" 
                            title="{{l('Invoicing Address', 'customerinvoices')}}" data-content="
                                  {{$customer->name_fiscal}}<br />
                                  {{l('VAT ID')}}: {{$customer->identification}}<br />
                                  {{ $customer->address->address1 }} {{ $customer->address->address2 }}<br />
                                  {{ $customer->address->postcode }} {{ $customer->address->city }}, {{ $customer->address->state->name }}<br />
                                  {{ $customer->address->country->name }}
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
            
          @include('cheques._panel_main_data')

          @include('cheques._panel_details')

          @include('cheques._panel_attachments')


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
         getChequeDetails();
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
