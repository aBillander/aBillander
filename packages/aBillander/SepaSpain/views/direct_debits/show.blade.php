@extends('layouts.master')

@section('title') {{ l('SEPA Direct Debits - Show') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

@if ( $directdebit->status == "pending" && ( $directdebit->nbrItems() != 0 ) )
        <a id="download_btn" href="{{ route('sepasp.directdebit.xml', $directdebit->id) }}" class="btn btn-success magick" style="margin-right: 22px;"><i class="fa fa-file-code-o"></i> &nbsp;{{ l('SEPA XML file') }}</a>
@endif

        <a class=" hide btn xbtn btn-info create-production-order" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-money"></i> &nbsp;{{l('Set as Paid')}}</a>

        <a href="{{ route('sepasp.directdebits.index') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to SEPA Direct Debits') }}</a>


                <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>

    </div>
    <h2>
        <a href="{{ route('sepasp.directdebits.index') }}">{{ l('SEPA Direct Debits') }}</a> <span style="color: #cccccc;">/</span> {{$directdebit->document_reference}} 

        <span class="lead well well-sm">

        <a class="btn btn-xs btn-warning" href="{{ URL::to('sepasp/directdebits/' . $directdebit->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

        <a title=" {{ l('Bank') }} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success text-center" data-toggle="popover" data-placement="right" title="" data-content="{{ l('Bank') }}: {{ optional($directdebit->bankaccount)->bank_name }}<br />{{ l('Group Vouchers?') }}&nbsp; {{ $directdebit->group_vouchers > 0 ? l('Yes', [], 'layouts') : l('No', [], 'layouts') }}<br />{{ l('Discount Remittance?') }}&nbsp; {{ $directdebit->discount_dd > 0 ? l('Yes', [], 'layouts') : l('No', [], 'layouts') }}" xdata-original-title="Datos de Facturación">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a> 
        </span>
        <span style="color: #cccccc;">::</span> 
        <a xclass="btn btn-sm btn-warning" href="{{ URL::to('sepasp/directdebits/' . $directdebit->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}">{{ abi_date_short($directdebit->document_date) }}</a> 


@if ( $directdebit->status == "pending" )
              <button type="button" class="btn btn-sm alert-danger" title="{{l('')}}">
                  <i class="fa fa-hand-stop-o"></i> {{$directdebit->status_name}}
              </button>
@endif
@if ( $directdebit->status == "confirmed" )
              <button type="button" class="btn btn-sm alert-warning" title="{{l('XML file at:')}} &nbsp;{{ abi_date_short($directdebit->validation_date) }}">
                  <i class="fa fa-hand-spock-o"></i> {{$directdebit->status_name}}
              </button>

        <a href="{{ route('sepasp.directdebit.unconfirm', [$directdebit->id]) }}" class="btn btn-xs btn-danger" 
        title="{{l('Undo', 'layouts')}}" xstyle="margin-left: 22px;"><i class="fa fa-undo"></i></a>

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
                  <h3 class="panel-title"><i class="fa fa-th-list"></i> &nbsp; {{ l('Customer Vouchers') }}

                        <span class=" pull-right label alert-info"  style="margin-right: 15px; font-size: 14px;">{{ l('Amount') }}: {{ $directdebit->as_money('total') }} &nbsp;/&nbsp; {{ l('Vouchers') }}: {{ $directdebit->nbrItems() }}</span>

                  </h3>
               </div>
                    @include('sepa_es::direct_debits._panel_customer_vouchers')
            </div>
      </div>
   </div>

</div>


@endsection



{{-- *************************************** --}}


{{--  --}}

    @include('sepa_es::direct_debits._modal_help')

{{--  --}}


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
