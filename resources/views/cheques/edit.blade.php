@extends('layouts.master')

@section('title') {{ l('Customer Cheques - Edit') }} @parent @stop


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- Button trigger modal -->
                <!-- button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_address" title=" Nueva Dirección Postal ">
                  <i class="fa fa-plus"></i> Dirección
                </button -->

            </div>

            <h2><a href="{{ URL::to('cheques') }}">{{ l('Customer Cheques') }}</a> <span style="color: #cccccc;">/</span> <span class="lead well well-sm alert-warning">{{$cheque->document_number}}</span> <span style="color: #cccccc;">/</span> 

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
            <a id="b_attachments" href="#attachments" class=" hide  list-group-item">
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
