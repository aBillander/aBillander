@extends('layouts.master')

@section('title') {{ l('SEPA Direct Debits - Show') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

@if ( $directdebit->status != "closed" && ( $directdebit->nbrItems() != 0 ) )
        <a id="download_btn" href="{{ route('sepasp.directdebit.xml', $directdebit->id) }}" class="btn btn-success magick" style="margin-right: 22px;""><i class="fa fa-file-code-o"></i> &nbsp;{{ l('SEPA XML file') }}</a>
@endif

        <a class=" hide btn xbtn btn-info create-production-order" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-money"></i> &nbsp;{{l('Set as Paid')}}</a>

        <a href="{{ route('sepasp.directdebits.index') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to SEPA Direct Debits') }}</a>
    </div>
    <h2>
        <a href="{{ route('sepasp.directdebits.index') }}">{{ l('SEPA Direct Debits') }}</a> <span style="color: #cccccc;">/</span> {{$directdebit->document_reference}} 

        <span class="lead well well-sm">
        <a title=" {{ l('Bank') }} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success text-center" data-toggle="popover" data-placement="right" title="" data-content="{{ optional($directdebit->bankaccount)->bank_name }}" xdata-original-title="Datos de Facturación">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a> 
        </span>
        <span style="color: #cccccc;">::</span> {{ abi_date_short($directdebit->document_date) }} 


@if ( $directdebit->status == "pending" )
              <button type="button" class="btn btn-sm alert-danger" title="{{l('')}}">
                  <i class="fa fa-hand-stop-o"></i> {{$directdebit->status_name}}
              </button>
@endif
@if ( $directdebit->status == "confirmed" )
              <button type="button" class="btn btn-sm alert-warning" title="{{l('XML file at:')}} &nbsp;{{ abi_date_short($directdebit->validation_date) }}">
                  <i class="fa fa-hand-spock-o"></i> {{$directdebit->status_name}}
              </button>
@endif
@if ( $directdebit->status == "closed" )
              <button type="button" class="btn btn-sm alert-success" title="{{l('')}}">
                  <i class="fa fa-thumbs-o-up"></i> {{$directdebit->status_name}}
              </button>
@endif


        <span class="badge" style="background-color: #3a87ad;" title="">{{ $directdebit->scheme }}</span>

@if ( $directdebit->group_vouchers )
              <button type="button" class="btn btn-xs btn-info" title="{{l('One Voucher for Customer and Date')}}">
                  <i class="fa fa-object-group"></i>
              </button>
@endif

@if ( $directdebit->discount_dd )
              <button type="button" class="btn btn-xs alert-danger" title="{{l('Financed Remittance')}}">
                  <i class="fa fa-calendar"></i>
              </button>
@endif

    </h2>        
</div>

    </div>
</div> 


<div class="container-fluid">
 

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         {{-- Poor man offset --}}
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-success" id="panel_production_orders">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-th-list"></i> &nbsp; {{ l('Customer Vouchers') }}</h3>
               </div>
                    @include('sepa_es::direct_debits._panel_customer_vouchers')
            </div>
      </div>
   </div>

</div>


@endsection


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {

      // Capture the "click" event of the link
      $('#download_btn').click( function ( evt ) {
            // Stop the link from doing what it would normally do.
            evt.preventDefault();
            // Open the file download in a new window. (It should just
            // show a normal file dialog)
            window.open(this.href, "_blank");

            // Then redirect the page you are on to whatever page you
            // want shown once the download has been triggered.
            setTimeout(function(){
                //do what you need here
                window.location = "{{ route('sepasp.directdebits.show', $directdebit->id) }}";
            }, 2500);

            return false;
      }).focusout (function(){
          // window.location.href = '/thank_you.html';
          // return false;
      });

});

</script>

@endsection
